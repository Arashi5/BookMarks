<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Localization\Loc;
use Bitrix\Highloadblock\HighloadBlockTable as HLBT;

$arComponentParameters = [
    "GROUPS" => [
    ],
    'PARAMETERS' => [
        'HL_ID' => [
            'NAME' => Loc::getMessage('HL_ID'),
            "DEFAULT" => '1',
            "TYPE" => 'STRING'
        ],
        'ELEMENT_COUNT' => [
            'NAME' => Loc::getMessage('PAGE_IS'),
            'DEFAULT' => '5',
            "TYPE" => 'STRING'
        ]
    ]
];