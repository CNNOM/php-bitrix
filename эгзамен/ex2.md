# [ex2-31] Подготовка
* В файле конфигурации Bitrix Framework включите вывод PHP-ошибок на страницах сайта.
https://hmarketing.ru/blog/bitrix/vklyuchenie-vyvoda-oshibok-v-fayle-settings/ 

```
  'exception_handling' =>
  array (
    'value' =>
    array (
      'debug' => true, // изменяем значение на true, если true то вывод ошибок включён
      'handled_errors_types' => 4437,
      'exception_errors_types' => 4437,
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


* Для журнала событий установите опции:
![image](https://github.com/user-attachments/assets/7728a7d4-ca02-49a2-8ee4-963e719a7fb9)

* Добавьте в шаблон сайте тег <meta>
![image](https://github.com/user-attachments/assets/078adcaa-fcf8-46c8-a2a0-f863907c9838)
![image](https://github.com/user-attachments/assets/256bd8ef-cf32-4004-8400-0fa4d902f747)
![image](https://github.com/user-attachments/assets/e43b6da6-1857-4b9b-bac3-b270ae9d2579)
![image](https://github.com/user-attachments/assets/6f14444a-1944-4fd5-90d1-aa2a5fed9831)






# [ex2-581] Кастомизация каталога товаров
```
			<div class="information-block-inner">
				<h3>Заголовок</h3>
				<? if ($arElement["PROPERTIES"]["Reviews"]["VALUE"]): ?>
					<h1>Отзывы</h1>
					<?
					$reviewIds = $arElement["PROPERTIES"]["Reviews"]["VALUE"];
					if (is_array($reviewIds)) {
						$reviews = CIBlockElement::GetList(
							array("SORT" => "ASC"),
							array("ID" => $reviewIds, "ACTIVE" => "Y"),
							false,
							false,
							array("ID", "IBLOCK_ID", "NAME", "PROPERTY_AUTHOR", "PROPERTY_TEXT")
						);

						while ($review = $reviews->GetNextElement()) {
							$fields = $review->GetFields();
							$props = $review->GetProperties();
					?>
							<div class="special-product">
								<? if ($props["Reviews"]["VALUE"]): ?>
									<div>Автор: <?= $props["Reviews"]["VALUE"] ?></div>
								<? endif; ?>
								<? if ($props["text"]["VALUE"]): ?>
									<div><?= $props["text"]["VALUE"] ?></div>
								<? endif; ?>
							</div>
					<?
						}
					}
					?>
				<? endif; ?>
			</div>
```
