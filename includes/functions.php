<?php

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function baseUrl() {
    return rtrim(BASE_URL, '/') . '/';
}

function appUrl($path = '') {
    $path = ltrim((string)$path, '/');
    return baseUrl() . $path;
}

function redirect($url) {
    if (preg_match('/^https?:\/\//i', $url)) {
        header('Location: ' . $url);
    } else {
        header('Location: ' . appUrl($url));
    }
    exit;
}

function isPost() {
    return ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST';
}

function flash($type, $message) {
    $_SESSION['flash'][] = array('type' => $type, 'message' => $message);
}

function getFlashes() {
    $messages = isset($_SESSION['flash']) ? $_SESSION['flash'] : array();
    unset($_SESSION['flash']);
    return $messages;
}

function csvOutput($filename, $headers, $rows) {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');

    $output = fopen('php://output', 'w');
    fputcsv($output, $headers, ';');
    foreach ($rows as $row) {
        fputcsv($output, $row, ';');
    }
    fclose($output);
    exit;
}
