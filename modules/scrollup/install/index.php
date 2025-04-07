<?php
/*
 * Файл local/modules/scrollup/install/index.php
 */

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Config\Option;
use Bitrix\Main\EventManager;
use Bitrix\Main\Application;
use Bitrix\Main\IO\Directory;

Loc::loadMessages(__FILE__);

class scrollup extends CModule
{

    public function __construct()
    {
        if (is_file(__DIR__ . '/version.php')) {
            include_once(__DIR__ . '/version.php');
            $this->MODULE_ID           = get_class($this);
            $this->MODULE_VERSION      = $arModuleVersion['VERSION'];
            $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
            $this->MODULE_NAME         = Loc::getMessage('SCROLLUP_NAME');
            $this->MODULE_DESCRIPTION  = Loc::getMessage('SCROLLUP_DESCRIPTION');
        } else {
            CAdminMessage::ShowMessage(
                Loc::getMessage('SCROLLUP_FILE_NOT_FOUND') . ' version.php'
            );
        }
    }

    public function DoInstall()
    {

        global $APPLICATION;

        // мы используем функционал нового ядра D7 — поддерживает ли его система?
        if (CheckVersion(ModuleManager::getVersion('main'), '14.00.00')) {
            // копируем файлы, необходимые для работы модуля
            $this->InstallFiles();
            // создаем таблицы БД, необходимые для работы модуля
            $this->InstallDB();
            // регистрируем модуль в системе
            ModuleManager::registerModule($this->MODULE_ID);
            // регистрируем обработчики событий
            $this->InstallEvents();
        } else {
            CAdminMessage::ShowMessage(
                Loc::getMessage('SCROLLUP_INSTALL_ERROR')
            );
            return;
        }

        $APPLICATION->IncludeAdminFile(
            Loc::getMessage('SCROLLUP_INSTALL_TITLE') . ' «' . Loc::getMessage('SCROLLUP_NAME') . '»',
            __DIR__ . '/step.php'
        );
    }

    public function InstallFiles()
    {
        // копируем js-файлы, необходимые для работы модуля
        CopyDirFiles(
            __DIR__ . '/assets/scripts',
            Application::getDocumentRoot() . '/bitrix/js/' . $this->MODULE_ID . '/',
            true,
            true
        );
        // копируем css-файлы, необходимые для работы модуля
        CopyDirFiles(
            __DIR__ . '/assets/styles',
            Application::getDocumentRoot() . '/bitrix/css/' . $this->MODULE_ID . '/',
            true,
            true
        );
    }

    public function InstallDB()
    {
        return;
    }

    public function InstallEvents()
    {
        // перед выводом буферизированного контента добавим свой HTML код,
        // в котором сохраним настройки для нашей кнопки прокрутки наверх

        echo '<script>alert("Message qqqqq");</script>';


        EventManager::getInstance()->registerEventHandler(
            'main',
            'OnBeforeEndBufferContent',
            $this->MODULE_ID,
            'ScrollUp\\Main',
            'appendJavaScriptAndCSS'
        );
    }

    public function DoUninstall()
    {

        global $APPLICATION;

        $this->UnInstallFiles();
        $this->UnInstallDB();
        $this->UnInstallEvents();

        ModuleManager::unRegisterModule($this->MODULE_ID);

        $APPLICATION->IncludeAdminFile(
            Loc::getMessage('SCROLLUP_UNINSTALL_TITLE') . ' «' . Loc::getMessage('SCROLLUP_NAME') . '»',
            __DIR__ . '/unstep.php'
        );
    }

    public function UnInstallFiles()
    {
        // удаляем js-файлы
        Directory::deleteDirectory(
            Application::getDocumentRoot() . '/bitrix/js/' . $this->MODULE_ID
        );
        // удаляем css-файлы
        Directory::deleteDirectory(
            Application::getDocumentRoot() . '/bitrix/css/' . $this->MODULE_ID
        );
        // удаляем настройки нашего модуля
        Option::delete($this->MODULE_ID);
    }

    public function UnInstallDB()
    {
        return;
    }

    public function UnInstallEvents()
    {
        // удаляем наш обработчик события
        EventManager::getInstance()->unRegisterEventHandler(
            'main',
            'OnBeforeEndBufferContent',
            $this->MODULE_ID,
            'ScrollUp\\Main',
            'appendJavaScriptAndCSS'
        );
    }
}
