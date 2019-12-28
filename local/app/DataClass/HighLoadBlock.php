<?php
namespace Custom\DataClass;

use Custom\DataClass\DataClass,
    Bitrix\Main\Loader,
    Bitrix\Highloadblock\HighloadBlockTable as HLBT;

require_once ($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

/**
 * Class HighLoadBlock
 * @package Custom\DataClass
 */
class HighLoadBlock implements DataClass
{
    /**
     *
     * @param int $hlBlockId
     * @return string
     */
    public function compileEntity(int $hlBlockId): string
    {
        if (Loader::includeModule('highloadblock')) {

            if ($hlBlockId) {
                $hlBlock = HLBT::getById($hlBlockId)->fetch();
                $entity = HLBT::compileEntity($hlBlock);

                return $entity->getDataClass();
            }
        }
    }
}