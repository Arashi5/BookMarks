<?php  
use  Custom\DataClass\HighLoadBlock;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

class BookMarksList extends \CBitrixComponent
{
    private $entity;

    public function onPrepareComponentParams($arParams)
    {
        return $result = [
            "IB_CODE" => $arParams['IB_CODE'],
            "HL_ID" => $arParams['HL_ID'],
            "FIELD_TYPE" => $arParams['FIELD_TYPE'],
            "ELEMENT_ID" => $arParams['ELEMENT_ID'],
        ];
    }

    public function executeComponent()
    {
        if ($this->startResultCache()) {

            // Получаем данные элемента из HLB
            // ID получаем из параметров компонента
            $this->setEntity(new HighLoadBlock, $this->arParams['HL_ID']);
            $this->arResult = ($this->entity)::getById($this->arParams['ELEMENT_ID'])->fetch();
            if ($this->arResult) {
                $this->arResult['UF_FAVICO'] = \CFile::GetPath($this->arResult['UF_FAVICO']);
            }
            $this->IncludeComponentTemplate();
        }
    }

    /**
     * Устанавливаем сущность
     *
     * @param $entity
     * @param $hlbId
     */
    private function setEntity($entity, $hlbId): void
    {
        $this->entity = $entity->compileEntity($hlbId);
    }

}

?>