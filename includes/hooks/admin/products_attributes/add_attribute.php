<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

class hook_admin_products_attributes_add_attribute {

  public function listen_injectBodyEnd() {
    global $action;
    
    $helper = <<<accordion
<script>document.addEventListener('DOMContentLoaded', function () { var active = sessionStorage.getItem('activeTab'); if (active) { var element = document.getElementById(active); if (element) { element.classList.add('show'); var nearestButton = document.querySelector('button[aria-controls="' + active + '"]'); if (nearestButton) { nearestButton.classList.remove('collapsed'); nearestButton.setAttribute('aria-expanded', 'true'); } } } document.getElementById('accordionAttributes').addEventListener('shown.bs.collapse', function (e) { sessionStorage.setItem('activeTab', e.target.id); }); });</script>

accordion;

    if ($action != 'update_attribute') {
      $helper .= <<<addat
<script>document.querySelectorAll('select[name="products_id"], select[name="options_id"], select[name="values_id"], input[name="price_prefix"], input[name="value_price"]').forEach(function (element) { element.required = true; }); document.querySelectorAll('select[name="options_id"], select[name="values_id"], input[name="price_prefix"], input[name="value_price"]').forEach(function (element) { element.disabled = true; }); document.querySelector('select[name="products_id"]').addEventListener('change', function () { document.querySelector('select[name="options_id"]').disabled = false; }); document.querySelector('select[name="options_id"]').addEventListener('change', function () { var valuesSelect = document.querySelector('select[name="values_id"]'); var selectedOptionId = this.value; valuesSelect.selectedIndex = 0; valuesSelect.disabled = false; valuesSelect.querySelectorAll('option').forEach(function (option) { if (option.dataset.id && !option.dataset.id.includes(selectedOptionId)) { option.style.display = 'none'; } else { option.style.display = ''; } }); }); document.querySelector('select[name="values_id"]').addEventListener('change', function () { document.querySelectorAll('input[name="value_price"], input[name="price_prefix"]').forEach(function (element) { element.disabled = false; }); });</script>

addat;
    }
    elseif ($action == 'update_attribute') {
      $helper .= <<<addat
<script>var selectedOption = document.querySelector('select[name="options_id"]').value; document.querySelectorAll('select[name="values_id"] option[data-id]').forEach(function (option) { if (!option.dataset.id.includes(selectedOption)) { option.style.display = 'none'; } }); document.querySelector('select[name="options_id"]').addEventListener('change', function () { var id = this.value; var valuesSelect = document.querySelector('select[name="values_id"]'); valuesSelect.selectedIndex = 0; valuesSelect.querySelectorAll('option[data-id]').forEach(function (option) { if (!option.dataset.id.includes(id)) { option.style.display = 'none'; } else { option.style.display = ''; } }); }); </script>

addat;
    }
    
    return $helper;
  } 

}
