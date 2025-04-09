# [ex2-31] Подготовка
* В файле конфигурации Bitrix Framework включите вывод PHP-ошибок на страницах сайта.
https://hmarketing.ru/blog/bitrix/vklyuchenie-vyvoda-oshibok-v-fayle-settings/ 

local/.settings.php
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

* Создайте тип информационного блока, код укажите «ex2». В нем:
	* В информационном блоке «Рецензии» добавьте два свойства:
	![image](https://github.com/user-attachments/assets/ef045014-73a2-4473-a01d-53ef8bad9918)

	* Создайте дополнительное поле для пользователей сайта
	![image](https://github.com/user-attachments/assets/e2864683-1f92-4a6d-803a-c75b19ceab97)





# [ex2-581] Кастомизация каталога товаров
*
catalog.section/test_catalog/result_modifier.php
```
<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    exit;
}
$reviews = CIBlockElement::GetList(
    array("SORT" => "ASC"), // Сортировка по возрастанию поля SORT
    array("IBLOCK_ID" => 53), // Фильтр по инфоблоку с ID = 55
    false, // Группировка не требуется
    array(), // Убираем параметры пагинации (не передаем false, а пустой массив)
    array("ID", "IBLOCK_ID", "NAME", "PROPERTY_AUTHOR", "PROPERTY_PRODUCT", "PREVIEW_TEXT") // Выбираемые поля
);

while ($review = $reviews->GetNext()) {
    foreach ($arResult["ITEMS"] as $key => $item) {
        if ($item["ID"] == $review["PROPERTY_PRODUCT_VALUE"]) {
            $rsUser = CUser::GetByID($review["PROPERTY_AUTHOR_VALUE"]);
            $arUser = $rsUser->Fetch();

            // Если имя или фамилия пустые, ставим заглушки
            $userName = !empty($arUser['NAME']) ? $arUser['NAME'] : "ЗаглушкаИмени";
            $userLastName = !empty($arUser['LAST_NAME']) ? $arUser['LAST_NAME'] : "ЗаглушкаФамилии";
            $authorName = $userName . " " . $userLastName;

            // Добавляем отзыв в массив REVIEWS товара
            $arResult['ITEMS'][$key]['REVIEWS'][] = [
                "NAME" => $review["NAME"],
                "PREVIEW_TEXT" => $review["PREVIEW_TEXT"],
                "AUTHOR" => $authorName,
            ];
        }
    }
}
```

catalog.section/test_catalog/template.php
```
<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<div>
	<style>
		.main {
			color: red;
		}

		h3 {
			color: green;
		}

		h4 {
			color: blue;
		}

		.rev {
			border: black solid 1px;
		}
	</style>
	<? foreach ($arResult["ITEMS"] as $item): ?>
		<div>
			<h1>
				<?= $item["NAME"] ?>
			</h1>
			<p>
				<?= $item["PREVIEW_TEXT"] ?>
			</p>
			<? if ($item['REVIEWS']): ?>
				<ul>

					<? foreach ($item['REVIEWS'] as $rev): ?>

						<li class="rev">
							<h3>
								<?= $rev["NAME"] ?>

							</h3>
							<h4>
								<?= $rev["AUTHOR"] ?>
							</h4>
							<p class='main'>
								<?= $rev["PREVIEW_TEXT"] ?>
							</p>


						</li>
					<? endforeach; ?>
				</ul>
			<? endif; ?>
		</div>
	<? endforeach; ?>
</div>
```
