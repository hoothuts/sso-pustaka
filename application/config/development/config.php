<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
$host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
$script_name = isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : '';
$dir = str_replace('\\', '/', dirname($script_name));
$sub = ($dir !== '/' && $dir !== '.') ? trim($dir, '/') . '/' : '';

$config['base_url'] = $protocol . $host . '/' . $sub;
