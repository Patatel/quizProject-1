<?php
header("Content-Type: application/json");
echo json_encode(["error" => "Aucune route spécifiée"]);
require_once __DIR__ . '/api/routes/api.php';
