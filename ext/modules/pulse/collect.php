<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2025 Phoenix Cart

  Released under the GNU General Public License
*/

chdir('../../../');

require 'includes/application_top.php';

header('Content-Type: application/json');

if (!empty($spider_flag)) {
  http_response_code(204); // No Content
  exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  echo json_encode(['error' => 'Method Not Allowed']);
  exit;
}

$content_type = strtolower(trim(explode(';', $_SERVER['CONTENT_TYPE'] ?? '')[0]));

if ($content_type !== 'application/json') {
  http_response_code(415);
  echo json_encode(['error' => 'Unsupported Media Type']);
  exit;
}

$raw = file_get_contents('php://input');
$data = json_decode($raw, true);

if (
  !is_array($data)
  || empty($data['event'])
  || !is_string($data['event'])
  || !preg_match('/^[A-Za-z0-9_.:\-]{1,64}$/', $data['event'])
) {  
  http_response_code(400);
  echo json_encode(['error' => 'Invalid event']);
  exit;
}

if (isset($data['payload']) && !is_array($data['payload'])) {
  http_response_code(400);
  echo json_encode(['error' => 'Invalid payload']);
  exit;
}

foreach (['page_url', 'referrer', 'domain', 'timestamp'] as $field) {
  if (isset($data[$field]) && !is_string($data[$field])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid ' . $field]);
    exit;
  }
}

// Extract product_id from payload if present
$payload_array = $data['payload'] ?? [];
$product_id = isset($payload_array['product_id']) ? (int)$payload_array['product_id'] : null;

// Remove product_id from payload to avoid duplication
unset($payload_array['product_id']);

// Escape and prepare data
$customer_id = isset($_SESSION['customer_id']) ? (int)$_SESSION['customer_id'] : "NULL";
$merchant_id = 0;
$event_type = $GLOBALS['db']->escape($data['event']);
$payload_json = $GLOBALS['db']->escape(json_encode($payload_array, JSON_UNESCAPED_UNICODE));
$page_url = $GLOBALS['db']->escape($data['page_url'] ?? '');
$referrer = $GLOBALS['db']->escape($data['referrer'] ?? '');
$domain = $GLOBALS['db']->escape($data['domain'] ?? '');
$user_agent = $GLOBALS['db']->escape($_SERVER['HTTP_USER_AGENT'] ?? '');
$ip_address = $GLOBALS['db']->escape(Request::get_ip());

// Convert ISO8601 or other timestamp to MySQL datetime, fallback to current time
$timestamp = $data['timestamp'] ?? '';
$created_at = date('Y-m-d H:i:s', strtotime($timestamp) ?: time());

// Build SQL query
$sql = "
  INSERT INTO analytics_events
    (customer_id, merchant_id, event_type, product_id, payload, page_url, referrer, domain, user_agent, ip_address, created_at)
  VALUES
    (
      $customer_id,
      $merchant_id,
      '$event_type',
      " . ($product_id !== null ? $product_id : "NULL") . ",
      '$payload_json',
      '$page_url',
      '$referrer',
      '$domain',
      '$user_agent',
      '$ip_address',
      '$created_at'
    )
";

// Execute query
$result = $GLOBALS['db']->query($sql);

if ($result === false) {
  http_response_code(500);
  echo json_encode(['error' => 'Database insert failed']);
  exit;
}

echo json_encode(['ok' => true]);
