# Lang
<div style="background:rgb(255, 255, 255); border-left: 4px solid #f44336; padding: 10px; margin: 10px 0; display: flex; align-items: center;">
  <span style="color: #f44336; font-weight: bold; margin-right: 10px;">!</span>
  <span style="color:rgb(0, 0, 0); font-weight: bold; margin-right: 10px;">Библеотка распологается в папке модуля \modules\dtcm.app</span>
</div>

\modules\dtcm.app\lang\ru\lib\TestModule\HelloManager.php

```php
<?
$MESS['NEW_AUTHOR'] = 'Текст сообщения';
?>
```

\modules\dtcm.app\lib\TestModule\HelloManager.php

Подключение
```php
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);


```

Вывод всех или одного текста:  
```php
$allMessages = Loc::loadLanguageFile(__FILE__);
$mess = Loc::getMessage('NEW_AUTHOR');
$APPLICATION->RestartBuffer();
echo '<pre>';
print_r($allMessages);
echo '</pre>';
exit();
```
