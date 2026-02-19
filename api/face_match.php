<?php
/**
 * ==========================================
 * Face Matching API (Mock / Extendable)
 * Smart Visitor Login System
 * ==========================================
 */

header("Content-Type: application/json");

// Simulated face matching logic
$response = [
    "match_score" => rand(75, 95),
    "threshold" => 80,
    "status" => "SUCCESS"
];

echo json_encode($response);
exit();