<li class="nav-item nb-search">
  <a class="nav-link border rounded bg-body-tertiary" style="width: 15rem;" href="#" data-bs-toggle="modal" data-bs-target="#searchModal">
    <?= MODULE_NAVBAR_SEARCH_PUBLIC_TEXT ?>
  </a>
</li>

<?php
$form = new Form('quick_find', $GLOBALS['Linker']->build('advanced_search_result.php')->set_include_session(false), 'get');
$form->hide_session_id()->hide('search_in_description', '0');

$search_text = TEXT_SEARCH_PLACEHOLDER;
$search_label = MODULE_NAVBAR_SEARCH_ARIA_LABEL;
$search_button = MODULE_NAVBAR_SEARCH_SEARCH_TEXT;

$advanced_search_link = $GLOBALS['Linker']->build('advanced_search.php');
$advanced_search_text = MODULE_NAVBAR_SEARCH_ADVANCED;

$input = new Input('keywords', ['autocomplete' => 'off', 'id' => 'keywords', 'placeholder' => $search_text, 'aria-label' => $search_label], 'search');
$input->require();

$searchModal = <<<SM
<div class="modal fade" id="searchModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded">
      <div class="modal-body p-5 pb-3">
        {$form}
          <div class="input-group input-group-lg">
            {$input}
            <button type="submit" aria-label="{$search_label}" class="btn btn-secondary btn-search">{$search_button}</button>
          </div>
        </form>
        <div class="mt-4 lead">
          <a href="{$advanced_search_link}">{$advanced_search_text}</a>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
const sModal = document.getElementById('searchModal')
const sInput = document.getElementById('keywords')

sModal.addEventListener('shown.bs.modal', () => {
  sInput.focus()
})
</script>
SM;

$GLOBALS['Template']->add_block($searchModal, 'footer_scripts');
?>

<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/
?>
