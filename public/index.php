<?php
require_once __DIR__ . "/../common/Config.php";
require_once __DIR__ . "/../common/Func.php";
require_once __DIR__ . "/../common/Loader.php";
require_once __DIR__ . "/../core/WebApplication.php";
$app = new WebApplication();
try {
    require_once  __DIR__ . "/../common/Route.php";
    if (!$app->prepare()) {
        $app->error();
    }
    $app->handle();
} catch (Exception $e) {
    echo json_encode(array(
        'code' => $e->getCode(),
        'message' => $e->getMessage(),
    ));
}
