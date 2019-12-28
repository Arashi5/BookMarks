<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Localization\Loc;

$arComponentParameters = [
    "GROUPS" => [
    ],
    'PARAMETERS' => [
        'HL_ID' => [
            'NAME' => Loc::getMessage('HL_ID'),
            "DEFAULT" => '10',
            "TYPE" => 'STRING'
        ],
        'ELEMENT_ID' => [
            'NAME' => Loc::getMessage('ELEMENT_ID'),
            "DEFAULT" => '',
            "TYPE" => 'STRING'
        ],
    ]
];