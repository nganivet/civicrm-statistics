<?php
require_once('stackapi.php');

$queries[] = array(
  'file' => 'stackexchange-info.json',
  'query' => "
      SELECT " . implode(',', $se_fields['site']) ."
        FROM stackexchange_history
       ORDER BY ts_created DESC
	   LIMIT 1
   ",
);
$fields = array();
foreach ($se_fields['site'] as $field) {
  $fields[] = "MAX($field) AS $field";
}
$queries[] = array(
  'file' => 'stackexchange-history.json',
  'query' => "
      SELECT LEFT(ts_created, 7) AS month,". implode(',', $fields) . "
        FROM stackexchange_history
       ORDER BY month ASC
   ",
);
$queries[] = array(
  'file' => 'stackexchange-top-users-by-reputation.json',
  'query' => "
      SELECT display_name, COALESCE(location, '') AS location, reputation
        FROM stackexchange_users
       ORDER BY reputation DESC
       LIMIT 100
   ",
);
$queries[] = array(
  'file' => 'stackexchange-top-users-by-accept-rate.json',
  'query' => "
      SELECT display_name, COALESCE(location, '') AS location, accept_rate
        FROM stackexchange_users
       ORDER BY accept_rate DESC
       LIMIT 100
   ",
);
$queries[] = array(
  'file' => 'stackexchange-top-users-by-badges.json',
  'query' => "
      SELECT display_name, COALESCE(location, '') AS location, badges_gold, badges_silver, badges_bronze
        FROM stackexchange_users
       ORDER BY badges_gold DESC, badges_silver DESC, badges_bronze DESC
       LIMIT 100
   ",
);