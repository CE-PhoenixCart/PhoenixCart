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

$raw = file_get_contents('php://input');
$data = json_decode($raw, true);

if (!is_array($data) || empty($data['event'])) {
  http_response_code(400);
  echo json_encode(['error' => 'Invalid event']);
  exit;
}

// Extract product_id from payload if present
$payload_array = $data['payload'] ?? [];
$product_id = isset($payload_array['product_id']) ? (int)$payload_array['product_id'] : null;

// Remove product_id from payload to avoid duplication
unset($payload_array['product_id']);

// Escape and prepare data
$customer_id = $_SESSION['customer_id'] ?? "NULL";
$merchant_id = 0;
$event_type = Text::input($data['event']);
$payload_json = Text::input(json_encode($payload_array, JSON_UNESCAPED_UNICODE));
$page_url = Text::input($data['page_url'] ?? '');
$referrer = Text::input($data['referrer'] ?? '');
$domain = Text::input($data['domain'] ?? '');
$user_agent = Text::input($_SERVER['HTTP_USER_AGENT'] ?? '');
$ip_address = Text::input(Request::get_ip());

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
