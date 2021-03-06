<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

class hook_admin_catalog_focusRequiredTab {

  public function listen_injectSiteEnd() {
    $focusTab = <<<'ft'
<script>
$(function () {
  $('button[type="submit"]').click(function() {
    var id = $('.tab-pane').find(':required:invalid').closest('.tab-pane').attr('id');

    $('.nav a[href="#' + id + '"]').tab('show'); $('.tab-pane').find(':required:invalid').closest('.collapse').addClass('show');

    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) { $("form[name='new_product']")[0].reportValidity(); })
  })
})
</script>
ft;

    return $focusTab;
  }

}
