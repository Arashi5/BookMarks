<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$APPLICATION->IncludeComponent(
    "custom:bookmarks.element",
    "",
    array(
        "COMPONENT_TEMPLATE" => "",
        "FIELD_TYPE" => "1",
        "IB_CODE" => "",
        "HL_ID" => $arParams['HL_ID'],
        "ELEMENT_ID" => $_REQUEST['ID']
    ),
    $component
);?>
