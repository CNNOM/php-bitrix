# [ex2-31] Подготовка
* В файле конфигурации Bitrix Framework включите вывод PHP-ошибок на страницах сайта.
https://hmarketing.ru/blog/bitrix/vklyuchenie-vyvoda-oshibok-v-fayle-settings/ 

local/.settings.php
```php
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
```html

<head>
    <? $prop = "ex2_meta"; ?>
    <meta propery="specialdate"
        name="<?= $prop ?>"
        content="<?= $APPLICATION->ShowProperty($prop) ?>">
```
![image](https://github.com/user-attachments/assets/256bd8ef-cf32-4004-8400-0fa4d902f747)
![image](https://github.com/user-attachments/assets/e43b6da6-1857-4b9b-bac3-b270ae9d2579)
![image](https://github.com/user-attachments/assets/6f14444a-1944-4fd5-90d1-aa2a5fed9831)

* Создайте тип информационного блока, код укажите «ex2». В нем:
	* В информационном блоке «Рецензии» добавьте два свойства:
	![image](https://github.com/user-attachments/assets/ef045014-73a2-4473-a01d-53ef8bad9918)

	* Создайте дополнительное поле для пользователей сайта
	![image](https://github.com/user-attachments/assets/e2864683-1f92-4a6d-803a-c75b19ceab97)





# [ex2-581] Кастомизация каталога товаров
* К описанию товара добавьте заголовок связанных с ним рецензий (на один товар может быть несколько рецензий), соответствующие условию
  
catalog.section/test_catalog/result_modifier.php
```php
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
```php
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

* Если в значении свойства страницы ex2_meta есть плейсхолдер #count#, заменить его на число
товаров, для которых выводятся рецензии. Решение должно работать корректно, в том числе
если текущее значение свойства страницы ex2_meta не указано явно ни в свойствах страницы,
ни через API, а наследуется от вышестоящего раздела.

```php
$prop = "ex2_meta";
$metaValue = $APPLICATION->GetProperty($prop);

if (strpos($metaValue, '#count#') !== false) {
    $count = 0;
    foreach ($arResult['ITEMS'] as $item) {
        if (!empty($item['REVIEWS'])) {
            $count++;
        }
    }

    // Заменяем плейсхолдер на реальное количество
    $metaValue = str_replace('#count#', $count, $metaValue);

    // // Устанавливаем обновленное значение
    $APPLICATION->SetPageProperty($prop, $metaValue);
}
Ï
```

 
# [ex2-590] Обновление элементов инфоблоков
* Проверять текста анонса при создании или обновлении рецензии:
	* Если текст анонса короче 5 символов, то отменять действие и показать пользователю
	уведомление об ошибке с текстом: «Текст анонса слишком короткий: [длина анонса]».
	* Если в тексте анонса присутствует плейсхолдер #del# - удалить его. 
	делать через модуль

	local/modules/testmodule.custom/include.php
	```php
	<?php
	if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
	
	require_once __DIR__ . "/functions.php";
	require_once __DIR__ . "/constants.php";
	require_once __DIR__ . "/lib/TestModule/HelloManager.php";
	
	$eventManager = \Bitrix\Main\EventManager::getInstance();
	
	$eventManager->addEventHandler('iblock', 'OnBeforeIBlockElementAdd', [
	    '\Local\TestModule\HelloManager',
	    'onBeforeElementAddUpdate'
	]);
	$eventManager->addEventHandler('iblock', 'OnBeforeIBlockElementUpdate', [
	    '\Local\TestModule\HelloManager',
	    'onBeforeElementAddUpdate'
	]);
	```
	
	local/modules/testmodule.custom/lib/TestModule/HelloManager.php
	```php
	<?php
	namespace Local\TestModule;
	
	class HelloManager
	{
	    public static function onBeforeElementAddUpdate(&$arFields)
	    {
	        // Проверяем, что это инфоблок с рецензиями (ID=53 из вашего примера)
	        if ($arFields['IBLOCK_ID'] != 53) {
	            return true;
	        }
	
	        // Проверка длины анонса
	        $previewText = trim($arFields['PREVIEW_TEXT']);
	        if (mb_strlen($previewText, 'UTF-8') < 5) {
	            $GLOBALS['APPLICATION']->ThrowException(
	                'Текст анонса слишком короткий: ' . mb_strlen($previewText, 'UTF-8') . ', а должен быть не меньше 5'
	            );
	            return false;
	        }
	
	        // Удаление плейсхолдера #del#
	        if (strpos($previewText, '#del#') !== false) {
	            $arFields['PREVIEW_TEXT'] = str_replace('#del#', '', $previewText);
	        }
	
	        return true;
	    }
	}
	```
 
	![image](https://github.com/user-attachments/assets/1cbb9352-4ac6-48e7-ac79-ed17abf52937)

* При обновлении рецензии проверить изменение поля «Автор». Если значение изменилось, и
какие-либо проверки (не только ваши) не отменили обновление, то сделать запись в журнал
(CEventLog::Add) «В рецензии [ID] изменился автор с [был ID автора] на [стал ID автора]»,
AUDIT_TYPE_ID укажите «ex2_590».

local/modules/testmodule.custom/include.php
```php

 $eventManager->addEventHandler('iblock', 'OnBeforeIBlockElementUpdate', [
    '\Local\TestModule\HelloManager',
    'onAfterElementUpdate'
]);


```

local/modules/testmodule.custom/lib/TestModule/HelloManager.php
```php
    public static function onAfterElementUpdate(&$arFields)
    {
	// Проверяем, что это инфоблок с рецензиями (ID=53)
	if ($arFields['IBLOCK_ID'] != 53) {
	    return; // Если это не наш инфоблок, выходим
	}
    
	$elementId = $arFields['ID']; // ID элемента
	$authorPropId = 113; // ID свойства "Автор"
	
	// Получаем новое значение автора
	$propValues = $arFields['PROPERTY_VALUES'][$authorPropId] ?? [];
	$newAuthorId = !empty($propValues) ? reset($propValues)['VALUE'] : null;
    
	// Получаем старое значение автора из свойств элемента
	$dbRes = \CIBlockElement::GetProperty(
	    $arFields['IBLOCK_ID'],
	    $elementId,
	    [],
	    ['CODE' => 'AUTHOR']
	);    

	if ($arProp = $dbRes->Fetch()) {
	    $oldAuthorId = $arProp['VALUE'];

	    // Если автор изменился
	    if ($oldAuthorId != $newAuthorId) {
		// Записываем в журнал через глобальное пространство имен
		\CEventLog::Add([
		    'SEVERITY' => 'INFO',
		    'AUDIT_TYPE_ID' => 'ex2_590',
		    'MODULE_ID' => 'iblock',
		    'ITEM_ID' => $elementId,
		    'DESCRIPTION' => sprintf(
			'В рецензии [%s] изменился автор с [%s] на [%s]',
			$elementId,
			$oldAuthorId,
			$newAuthorId
		    ),
		]);
	    }
	}
    }
```

![image](https://github.com/user-attachments/assets/953a6031-2355-40b9-862e-dbf89525d2ea)

