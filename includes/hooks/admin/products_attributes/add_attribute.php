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
<script>
document.addEventListener('DOMContentLoaded', function () {
  var active = sessionStorage.getItem('activeTab');
  if (active) {
    var element = document.getElementById(active);
    if (element) {
      element.classList.add('show');
      var nearestButton = document.querySelector('button[aria-controls="' + active + '"]');
      if (nearestButton) {
        nearestButton.classList.remove('collapsed');
        nearestButton.setAttribute('aria-expanded', 'true');
      }
    }
  }
  document.getElementById('accordionAttributes').addEventListener('shown.bs.collapse', function (e) {
    sessionStorage.setItem('activeTab', e.target.id);
  });
});
</script>
accordion;

    if ($action != 'update_attribute') {
        // New attribute form
        $helper .= <<<addat
<script>
document.addEventListener('DOMContentLoaded', function() {
  const productsSelect = document.querySelector('select[name="products_id"]');
  const optionsSelect = document.querySelector('select[name="options_id"]');
  const valuesSelect = document.querySelector('select[name="values_id"]');
  const priceInputs = document.querySelectorAll('input[name="value_price"], input[name="price_prefix"]');

  function resetSelect(select) {
    select.selectedIndex = 0;
    select.disabled = true;
    select.querySelectorAll('option').forEach(option => option.hidden = false);
  }

  function disableInputs(inputs) {
    inputs.forEach(el => el.disabled = true);
  }

  document.querySelectorAll('select[name="products_id"], select[name="options_id"], select[name="values_id"], input[name="price_prefix"], input[name="value_price"]').forEach(el => el.required = true);

  resetSelect(optionsSelect);
  resetSelect(valuesSelect);
  disableInputs(priceInputs);

  productsSelect.addEventListener('change', function() {
    resetSelect(optionsSelect);
    resetSelect(valuesSelect);
    disableInputs(priceInputs);
    if (this.value) optionsSelect.disabled = false;
  });

  optionsSelect.addEventListener('change', function() {
    resetSelect(valuesSelect);
    disableInputs(priceInputs);
    if (!this.value) return;
    valuesSelect.disabled = false;
    valuesSelect.querySelectorAll('option').forEach(option => {
      if (option.dataset.id) {
        option.hidden = !option.dataset.id.split(',').includes(this.value);
      }
    });
  });

  valuesSelect.addEventListener('change', function() {
    disableInputs(priceInputs);
    if (this.value) priceInputs.forEach(el => el.disabled = false);
  });
});
</script>
addat;
    } else {
        // Update attribute form
        $helper .= <<<addat
<script>
document.addEventListener('DOMContentLoaded', function() {
  const optionsSelect = document.querySelector('select[name="options_id"]');
  const valuesSelect = document.querySelector('select[name="values_id"]');

  function resetValues() {
    valuesSelect.selectedIndex = 0;
    valuesSelect.querySelectorAll('option[data-id]').forEach(option => option.hidden = false);
  }

  const selectedOption = optionsSelect.value;
  valuesSelect.querySelectorAll('option[data-id]').forEach(option => {
    if (selectedOption && !option.dataset.id.split(',').includes(selectedOption)) option.hidden = true;
  });

  optionsSelect.addEventListener('change', function() {
    resetValues();
    const id = this.value;
    if (!id) return;
    valuesSelect.querySelectorAll('option[data-id]').forEach(option => {
      option.hidden = !option.dataset.id.split(',').includes(id);
    });
  });
});
</script>
addat;
    }

    return $helper;
  }
}