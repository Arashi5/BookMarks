<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$request = Bitrix\Main\Application::getInstance()->getContext()->getRequest();
global $APPLICATION;
?>
<? if ($request->isAjaxRequest() && $request->get("reload")) $APPLICATION->RestartBuffer(); ?>
<div class="append-data-list">
    <?

    if ($request->get('by') &&$request->get('order')) {
        $sort['BY'] = $request->get('by');
        $sort['ORDER'] = $request->get('order');
    }

    $APPLICATION->IncludeComponent(
        "custom:bookmarks.list",
        "",
        array(
            "COMPONENT_TEMPLATE" => "",
            "FIELD_TYPE" => "1",
            "IB_CODE" => "",
            "HL_ID" => $arParams['HL_ID'],
            "ELEMENT_COUNT" => $arParams['ELEMENT_COUNT'],
            "SORT"=> ($sort)?$sort:"",
        ),
        $component
    ); ?>
</div>
<? if ($request->isAjaxRequest() && $request->get("reload")) die() ?>
