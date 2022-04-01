<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/
?>

  </div>

  <?php
  if (isset($_SESSION['admin'])) {
    require 'includes/footer.php';
  }

  echo $admin_hooks->cat('injectSiteEnd');
  ?>

  </div>
</div>

<?= $admin_hooks->cat('injectBodyEnd') ?>

</body>
</html>
