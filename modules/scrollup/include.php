<?php
/*
 * Файл local/modules/scrollup/include.php
 */
/*
CModule::AddAutoloadClasses(
    'scrollup',
    array(
        'ScrollUp\\Main' => 'lib/Main.php',
    )
);
*/
Bitrix\Main\Loader::registerAutoloadClasses(
    'scrollup',
    array(
        'ScrollUp\\Main' => 'lib/Main.php',
    )
);
