<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/
?>

<footer class="mt-3 d-print-none">
  <div class="pt-3 bg-body-secondary border-top border-2">
    <div class="container-fluid">
      <div class="row">
        <div class="col-9 text-start">
          <p>Phoenix v<?= Versions::get('Phoenix') ?> &copy; 2000-<?= date('Y') ?> <a class="text-reset link-offset-3" href="https://phoenixcart.org" target="_blank" rel="noreferrer">Phoenix Cart</a></p>
        </div>

        <div class="col-3 text-end">
          <p><a href="#" id="back-to-top" aria-label="Back to top" class="link-secondary text-decoration-none small"><i aria-hidden="true" class="fa-solid fa-arrow-up"></i></a></p>
        </div>
      </div>
    </div>
  </div>
  
</footer>

<script>
document.getElementById('back-to-top').addEventListener('click', function (e) {
  e.preventDefault();
  var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  window.scrollTo({ top: 0, behavior: reduce ? 'auto' : 'smooth' });
});
</script>

