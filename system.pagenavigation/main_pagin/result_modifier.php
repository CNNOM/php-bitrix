<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    exit;
}
$strNavQueryString = $arResult['NavQueryString'];
$strNavQueryString = preg_replace('/(?:^|&)page=[^&]*/', '', $strNavQueryString);
$strNavQueryString = $strNavQueryString != '' ? $strNavQueryString . '&amp;' : '';


$strNavQueryStringFull = $arResult['NavQueryString'] != '' ? '?' . $arResult['NavQueryString'] : '';


if (!$arResult["NavShowAlways"]) {
    if ($arResult["NavRecordCount"] == 0 || ($arResult["NavPageCount"] == 1 && $arResult["NavShowAll"] == false)) {
        return;
    }
}

$arResult['reduct'] = [
    'prev_page' => [
        'conditions' => $arResult['NavPageNomer'] > 2,
        'link_1' => $arResult['sUrlPath'] . '?' . $strNavQueryString . 'page' . '=' . ($arResult["NavPageNomer"] - 1),
        'link_2' => $strNavQueryStringFull
    ],
    'pages' => [
        'nStartPage' => $arResult["nStartPage"],
        'link' => $arResult["sUrlPath"] . '?' . $strNavQueryString,
    ],
    'other_page' => [
        'link_next' => $arResult["sUrlPath"] . '?' . $strNavQueryString . 'page' . '=' . ($arResult["NavPageNomer"] + 1),
        'link_back' => $arResult["sUrlPath"] . '?' . $strNavQueryString . 'page' . '='  . ($arResult["NavPageNomer"] - 1),
        'link_first' => $arResult["sUrlPath"] . '?' . $strNavQueryString . 'page' . '=' . 1,
        'link_end' => $arResult["sUrlPath"] . '?' . $strNavQueryString . 'page' . '=' . $arResult["NavPageCount"]
    ],
    'show_more' => [
        'link' => $arResult["sUrlPath"] . '?' . $strNavQueryString . 'page' . '=' . ($arResult["NavPageNomer"] + 1)
    ],
];
// echo '<pre>';
// print_r($arResult);
// echo '</pre>';
// echo '<pre>';
// print_r($_GET);
// echo '</pre>';
