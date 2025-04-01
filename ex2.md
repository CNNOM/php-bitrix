# [ex2-31] Подготовка

* В файле конфигурации Bitrix Framework включите вывод PHP-ошибок на страницах сайта.

```
  'exception_handling' =>
  array (
    'value' =>
    array (
      'debug' => true,
      'handled_errors_types' => E_ALL,
      'exception_errors_types' => E_ALL,
      'ignore_silence' => false,
      'assertion_throws_exception' => true,
      'assertion_error_type' => 256,
      'log' => array (
          'settings' =>
          array (
            'file' => getenv('BITRIX_ERROR_LOG_FILE_PATH'),
            'log_size' => getenv('BITRIX_ERROR_LOG_FILE_SIZE'),
        ),
      ),
    ),
    'readonly' => false,
  ),
```
