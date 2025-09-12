<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2025 Phoenix Cart

  Released under the GNU General Public License
*/
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<?php
$lid = (int)$_SESSION['languages_id'];
$products = $GLOBALS['db']->query("SELECT products_id, products_name FROM products_description WHERE language_id = $lid ORDER BY products_name");
?>

<form method="GET" action="pulse_analytics.php" class="row g-3 align-items-end mb-4">
  <input type="hidden" name="action" value="product_view">
  <div class="col-md-5">
    <div class="form-floating">
      <select name="product_id" id="product_id" class="form-select" required>
        <option value="" disabled <?= (!isset($_GET['product_id']) || $_GET['product_id'] === '') ? 'selected' : '' ?>><?= PRODUCT_VIEW_CHOOSE_PRODUCT ?></option>
        <option value="all" <?= (isset($_GET['product_id']) && $_GET['product_id'] == 'all') ? 'selected' : '' ?>><?= PRODUCT_VIEW_ALL_PRODUCTS ?></option>
        <?php while ($row = $products->fetch_assoc()) : ?>
          <option value="<?= (int)$row['products_id'] ?>" <?= (isset($_GET['product_id']) && $_GET['product_id'] == $row['products_id']) ? 'selected' : '' ?>>
            <?= htmlspecialchars($row['products_name']) ?>
          </option>
        <?php endwhile; ?>
      </select>
      <label for="product_id"><?= PRODUCT_VIEW_CHOOSE_LABEL ?></label>
    </div>
  </div>
  <div class="col-md-3">
    <div class="form-floating">
      <input type="date" id="start_date" name="start_date" class="form-control" required
        value="<?= isset($_GET['start_date']) ? htmlspecialchars($_GET['start_date']) : date('Y-m-01') ?>">
      <label for="start_date"><?= PRODUCT_VIEW_START_DATE ?></label>
    </div>
  </div>
  <div class="col-md-3">
    <div class="form-floating">
      <input type="date" id="end_date" name="end_date" class="form-control" required
        value="<?= isset($_GET['end_date']) ? htmlspecialchars($_GET['end_date']) : date('Y-m-d') ?>">
      <label for="end_date"><?= PRODUCT_VIEW_END_DATE ?></label>
    </div>
  </div>
  <div class="col-md-1">
    <button type="submit" class="btn btn-primary w-100"><?= BUTTON_FILTER ?></button>
  </div>
</form>

<?php
$product_id = isset($_GET['product_id']) ? $_GET['product_id'] : '';
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01');
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d');

if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $start_date) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $end_date)) {
  echo ERROR_INVALID_DATE_FORMAT;
  exit;
}

if ($product_id !== '' && $product_id !== '0') {
  if ($product_id === 'all') {
    $views_sql = $GLOBALS['db']->prepare("
      SELECT DATE(created_at) as view_date, COUNT(*) as views
      FROM analytics_events
      WHERE event_type = 'product_view'
        AND created_at BETWEEN ? AND DATE_ADD(?, INTERVAL 1 DAY)
      GROUP BY view_date
      ORDER BY view_date ASC
    ");
    $views_sql->bind_param("ss", $start_date, $end_date);
  } else {
      $product_id_int = (int)$product_id;
      $views_sql = $GLOBALS['db']->prepare("
        SELECT DATE(created_at) as view_date, COUNT(*) as views
        FROM analytics_events
        WHERE event_type = 'product_view'
          AND product_id = ?
          AND created_at BETWEEN ? AND DATE_ADD(?, INTERVAL 1 DAY)
        GROUP BY view_date
        ORDER BY view_date ASC
      ");
      $views_sql->bind_param("iss", $product_id_int, $start_date, $end_date);
    }

    $views_sql->execute();
    $views_result = $views_sql->get_result();

    $views_by_date = [];
    while ($row = $views_result->fetch_assoc()) {
      $views_by_date[$row['view_date']] = (int)$row['views'];
    }

    if ($product_id === 'all') {
      $sales_sql = $GLOBALS['db']->prepare("
        SELECT DATE(o.date_purchased) as sale_date, SUM(op.products_quantity) as sales
        FROM orders o
        JOIN orders_products op ON o.orders_id = op.orders_id
        WHERE o.date_purchased BETWEEN ? AND DATE_ADD(?, INTERVAL 1 DAY)
        GROUP BY sale_date
        ORDER BY sale_date ASC
      ");
      $sales_sql->bind_param("ss", $start_date, $end_date);
    } else {
      $sales_sql = $GLOBALS['db']->prepare("
        SELECT DATE(o.date_purchased) as sale_date, SUM(op.products_quantity) as sales
        FROM orders o
        JOIN orders_products op ON o.orders_id = op.orders_id
        WHERE op.products_id = ?
          AND o.date_purchased BETWEEN ? AND DATE_ADD(?, INTERVAL 1 DAY)
        GROUP BY sale_date
        ORDER BY sale_date ASC
      ");
      $sales_sql->bind_param("iss", $product_id_int, $start_date, $end_date);
    }

    $sales_sql->execute();
    $sales_result = $sales_sql->get_result();

    $sales_by_date = [];
    while ($row = $sales_result->fetch_assoc()) {
      $sales_by_date[$row['sale_date']] = (int)$row['sales'];
    }

    $period = new DatePeriod(
      new DateTime($start_date), new DateInterval('P1D'), (new DateTime($end_date))->modify('+1 day')
    );

    $labels = [];
    $views_data = [];
    $sales_data = [];

    foreach ($period as $date) {
      $d = $date->format('Y-m-d');
      $labels[] = $date->format('M j');
      $views_data[] = $views_by_date[$d] ?? 0;
      $sales_data[] = $sales_by_date[$d] ?? 0;
    }
  } else {
    $labels = [];
    $views_data = [];
    $sales_data = [];
  }
?>

<?php if ($product_id !== '' && $product_id !== '0'): ?>
<div class="card mb-4">
  <div class="card-header d-flex justify-content-between">
    <h5 class="mb-0"><?= PRODUCT_VIEW_CHART_TITLE ?><?= $product_id === 'all' ? PRODUCT_VIEW_CHART_TITLE_ALL : '' ?></h5>
    <small class="text-muted">
      <?= sprintf(PRODUCT_VIEW_CHART_TITLE_DATE_RANGE, htmlspecialchars($start_date), htmlspecialchars($end_date)) ?></small>
  </div>
  <div class="card-body">
    <canvas id="analyticsChart" height="400"></canvas>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const ctx = document.getElementById('analyticsChart');

  if (ctx) {
    const chart = new Chart(ctx, {
      type: 'line',
      data: {
        labels: <?= json_encode($labels) ?>,
        datasets: [
          {
            label: 'Views',
            data: <?= json_encode($views_data) ?>,
            fill: false,
            backgroundColor: 'rgba(13, 110, 253, 0.2)',
            borderColor: 'rgba(13, 110, 253, 1)',
            borderWidth: 2,
            pointRadius: 4,
            pointHoverRadius: 6,
            tension: 0.3
          },
          {
            label: 'Sales',
            data: <?= json_encode($sales_data) ?>,
            fill: false,
            backgroundColor: 'rgba(25, 135, 84, 0.2)',
            borderColor: 'rgba(25, 135, 84, 1)',
            borderWidth: 2,
            pointRadius: 4,
            pointHoverRadius: 6,
            tension: 0.3
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: {
          mode: 'index',
          intersect: false
        },
        plugins: {
          tooltip: {
            mode: 'index',
            intersect: false
          },
          legend: {
            display: true,
            labels: {
              font: { size: 14 }
            }
          }
        },
        scales: {
          x: {
            title: {
              display: true,
              text: 'Date'
            }
          },
          y: {
            beginAtZero: true,
            title: {
              display: true,
              text: 'Count'
            },
            ticks: {
              precision: 0
            }
          }
        }
      }
    });
  }
});
</script>
<?php endif; ?>