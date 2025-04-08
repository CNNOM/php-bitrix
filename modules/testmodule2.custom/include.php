<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

require_once __DIR__ . "/functions.php";
require_once __DIR__ . "/constants.php";

\Bitrix\Main\Loader::registerAutoLoadClasses(null, [
    '\Local\TestModule\HelloManager' => '/local/modules/testmodule.custom/lib/TestModule/HelloManager.php',
]);