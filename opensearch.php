<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  header('Content-Type: text/xml');

  require 'includes/application_top.php';

  if ( !defined('MODULE_HEADER_TAGS_OPENSEARCH_STATUS') || (MODULE_HEADER_TAGS_OPENSEARCH_STATUS != 'True') ) {
    exit();
  }

  echo '<?xml version="1.0"?>' . "\n";
?>
<OpenSearchDescription xmlns="http://a9.com/-/spec/opensearch/1.1/" xmlns:moz="http://www.mozilla.org/2006/browser/search/">
  <ShortName><?= Text::output(MODULE_HEADER_TAGS_OPENSEARCH_SITE_SHORT_NAME) ?></ShortName>
  <Description><?= Text::output(MODULE_HEADER_TAGS_OPENSEARCH_SITE_DESCRIPTION) ?></Description>
<?php
  if (!Text::is_empty(MODULE_HEADER_TAGS_OPENSEARCH_SITE_CONTACT)) {
    echo '  <Contact>' . Text::output(MODULE_HEADER_TAGS_OPENSEARCH_SITE_CONTACT) . '</Contact>' . "\n";
  }

  if (!Text::is_empty(MODULE_HEADER_TAGS_OPENSEARCH_SITE_TAGS)) {
    echo '  <Tags>' . Text::output(MODULE_HEADER_TAGS_OPENSEARCH_SITE_TAGS) . '</Tags>' . "\n";
  }

  if (!Text::is_empty(MODULE_HEADER_TAGS_OPENSEARCH_SITE_ATTRIBUTION)) {
    echo '  <Attribution>' . Text::output(MODULE_HEADER_TAGS_OPENSEARCH_SITE_ATTRIBUTION) . '</Attribution>' . "\n";
  }

  if (MODULE_HEADER_TAGS_OPENSEARCH_SITE_ADULT_CONTENT == 'True') {
    echo '  <AdultContent>True</AdultContent>' . "\n";
  }

  if (!Text::is_empty(MODULE_HEADER_TAGS_OPENSEARCH_SITE_ICON)) {
    echo '  <Image height="16" width="16" type="image/x-icon">' . Text::output(MODULE_HEADER_TAGS_OPENSEARCH_SITE_ICON) . '</Image>' . "\n";
  }

  if (!Text::is_empty(MODULE_HEADER_TAGS_OPENSEARCH_SITE_IMAGE)) {
    echo '  <Image height="64" width="64" type="image/png">' . Text::output(MODULE_HEADER_TAGS_OPENSEARCH_SITE_IMAGE) . '</Image>' . "\n";
  }
?>
  <InputEncoding>UTF-8</InputEncoding>
  <Url type="text/html" method="get" template="<?= $Linker->build('advanced_search_result.php', ['keywords' => '{searchTerms}'], false) ?>" />
</OpenSearchDescription>
<?php
  require 'includes/application_bottom.php';
?>
