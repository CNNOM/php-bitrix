<?php
namespace MyCompany\Custom\EventHandlers;

class Main
{
    // Редерект на главну страницу если чел зашёл на страницу test (любой) и он не является админом
    static function redirectFromTestPage(): void
    {
        global $USER, $APPLICATION;
        $curPage = $APPLICATION->GetCurPage();
        if(str_ends_with($curPage, 'test.php') && !$USER->IsAdmin())
        {
            LocalRedirect('/');
        }
    }
}