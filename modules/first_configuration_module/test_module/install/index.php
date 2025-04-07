<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Config\Option;
use Bitrix\Main\EventManager;
use Bitrix\Main\Application;
use Bitrix\Main\IO\Directory;

Loc::loadMessages(__FILE__);

class test_module extends CModule
{
    public function __construct()
    {
        if (is_file(__DIR__ . '/version.php')) {
            include_once(__DIR__ . '/version.php');
            $this->MODULE_ID           = get_class($this);
            $this->MODULE_VERSION      = $arModuleVersion['VERSION'];
            $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
            $this->MODULE_NAME         = Loc::getMessage('TEST_MODULE_NAME');
            $this->MODULE_DESCRIPTION  = Loc::getMessage('TEST_MODULE_DESC');
        } else {
            CAdminMessage::ShowMessage(
                Loc::getMessage('SCROLLUP_FILE_NOT_FOUND') . ' version.php'
            );
        }
    }
    public function DoInstall()
    {

        global $APPLICATION;

        ModuleManager::registerModule($this->MODULE_ID);

        $APPLICATION->IncludeAdminFile(
            Loc::getMessage('SCROLLUP_INSTALL_TITLE') . ' «' . Loc::getMessage('SCROLLUP_NAME') . '»',
            __DIR__ . '/step.php'
        );
    }

    public function DoUninstall()
    {

        global $APPLICATION;

        ModuleManager::unRegisterModule($this->MODULE_ID);

        $APPLICATION->IncludeAdminFile(
            Loc::getMessage('SCROLLUP_UNINSTALL_TITLE') . ' «' . Loc::getMessage('SCROLLUP_NAME') . '»',
            __DIR__ . '/unstep.php'
        );
    }
}
