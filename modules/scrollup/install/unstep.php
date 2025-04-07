<?php
/*
 * Файл local/modules/scrollup/install/unstep.php
 */

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

if (!check_bitrix_sessid()){
    return;
}

if ($errorException = $APPLICATION->GetException()) {
    // ошибка при удалении модуля
    CAdminMessage::ShowMessage(
        Loc::getMessage('SCROLLUP_UNINSTALL_FAILED').': '.$errorException->GetString()
    );
} else {
    // модуль успешно удален
    CAdminMessage::ShowNote(
        Loc::getMessage('SCROLLUP_UNINSTALL_SUCCESS')
    );
}
?>

<form action="<?= $APPLICATION->GetCurPage(); ?>"> <!-- Кнопка возврата к списку модулей -->
    <input type="hidden" name="lang" value="<?= LANGUAGE_ID; ?>" />
    <input type="submit" value="<?= Loc::getMessage('SCROLLUP_RETURN_MODULES'); ?>">
</form>