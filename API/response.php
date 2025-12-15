<?php
function jsonResponse(bool $success, string $message, $data = null, int $code = 200): void
{
  http_response_code($code);
  echo json_encode([
    "success" => $success,
    "message" => $message,
    "data" => $data
  ]);
  exit;
}
