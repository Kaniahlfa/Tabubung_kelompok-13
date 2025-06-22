<?php
// index.php (Unified Front Controller)

// 1. Tampilkan semua error saat development
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 2. BASE_URL Configuration
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'];
$scriptName = dirname($_SERVER['SCRIPT_NAME']);
$basePath = ($scriptName === '/' || $scriptName === '\\') ? '' : $scriptName;
define('BASE_URL', rtrim($protocol . $host . $basePath, '/') . '/');

// 3. Load base controller class
$coreControllerPath = __DIR__ . '/core/Controller.class.php';
$altControllerPath = __DIR__ . '/Controller/Controller.class.php';
if (file_exists($coreControllerPath)) {
    require_once $coreControllerPath;
} elseif (file_exists($altControllerPath)) {
    require_once $altControllerPath;
} else {
    die("⚠️ Base controller tidak ditemukan.");
}

// 4. Default controller & method
$defaultController = 'Transaction';
$defaultMethod = 'index';

// 5. Ambil controller dan method dari URL parameter (?c=Controller&m=method)
$controllerName = isset($_GET['c']) ? basename($_GET['c']) : $defaultController;
$methodName = isset($_GET['m']) ? basename($_GET['m']) : $defaultMethod;
$params = [];

// 6. Alternatif: Ambil controller dan method dari format ?url=controller/method/param
if (isset($_GET['url'])) {
    $url = rtrim($_GET['url'], '/');
    $url = filter_var($url, FILTER_SANITIZE_URL);
    $urlParts = explode('/', $url);

    if (!empty($urlParts[0])) {
        $controllerName = ucwords(str_replace('-', '', $urlParts[0])) . 'Controller';
    }
    if (isset($urlParts[1]) && !empty($urlParts[1])) {
        $methodNameParts = explode('-', $urlParts[1]);
        $methodName = array_shift($methodNameParts);
        foreach ($methodNameParts as $part) {
            $methodName .= ucwords($part);
        }
    }
    if (count($urlParts) > 2) {
        $params = array_slice($urlParts, 2);
    }
}

// 7. Tentukan path controller
$controllerFile = __DIR__ . '/controller/' . $controllerName . '.class.php';
if (!file_exists($controllerFile)) {
    $controllerFile = __DIR__ . '/Controller/' . $controllerName . '.class.php';
}

// 8. Eksekusi controller dan method
if (file_exists($controllerFile)) {
    require_once $controllerFile;
    if (class_exists($controllerName)) {
        $controllerInstance = new $controllerName();
        if (method_exists($controllerInstance, $methodName)) {
            call_user_func_array([$controllerInstance, $methodName], $params);
        } else {
            http_response_code(404);
            echo "⚠️ Method '$methodName' tidak ditemukan di controller '$controllerName'.";
        }
    } else {
        http_response_code(404);
        echo "⚠️ Controller class '$controllerName' tidak ditemukan.";
    }
} else {
    http_response_code(404);
    echo "⚠️ File controller '$controllerFile' tidak ditemukan.";
}
?>
