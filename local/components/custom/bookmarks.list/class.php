<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Application,
    Bitrix\Main\Localization\Loc,
    Custom\DataClass\HighLoadBlock,
    Custom\ExcelCreate;

class BookMarksList extends \CBitrixComponent
{
    /**
     * сущность
     */
    private $entity;

    /**
     * Фильтр
     * @var array
     */
    private $arFilter = [];

    /**
     * Количество элементов н страницу
     * @var array
     */
    private $offset = [];

    /**
     * сортировка
     *
     * @var array
     */
    private $order = [];

    public function onPrepareComponentParams($arParams)
    {
        return $result = [
            "IB_CODE" => $arParams['IB_CODE'],
            "HL_ID" => $arParams['HL_ID'],
            "FIELD_TYPE" => $arParams['FIELD_TYPE'],
            "ELEMENT_COUNT"=>$arParams['ELEMENT_COUNT'],
            "SORT" => $arParams['SORT'],
        ];
    }

    public function executeComponent()
    {
        if ($this->startResultCache()) {

            $request = Application::getInstance()
                ->getContext()
                ->getRequest();
            $this->setEntity(new HighLoadBlock, $this->arParams['HL_ID']);

            if (intval($request->get('page')) > 1) {
               $this->setOffset($request->get('page'));
            }

            if (!$this->arParams['SORT']) {
                $this->order['ORDER']= "desc";
                $this->order['BY'] = "UF_CREATED_AT";
            } else {
                $this->order['ORDER'] = $this->arParams['SORT']['ORDER'];
                $this->order['BY'] = $this->arParams['SORT']['BY'];
            }

            $this->arResult['ITEMS'] = $this->getBookMarkList();
            $this->arResult['NAVIGATION'] = $this->navigation();
            $this->arResult['EXCEL'] = $this->getExcel()->getTempName();
            $this->arResult['SORT_LIST'] = $this->getSortList();

            $this->IncludeComponentTemplate();
        }
    }

    /**
     * Установка количества элементов на страницу
     *
     * @param $pageNumber
     * @return void
     */
    private function setOffset($pageNumber): void
    {
        $this->offset = ($pageNumber * $this->arParams['ELEMENT_COUNT']) - $this->arParams['ELEMENT_COUNT'];
    }

    /**
     * Возвращаем поля для сортировки
     *
     * @return array
     */
    private function getSortList(): array
    {
        return [
            Loc::getMessage('BY_TITLE') => 'UF_TITLE',
            Loc::getMessage('BY_DATE')  => 'UF_CREATED_AT',
            Loc::getMessage('BY_URL')   => 'UF_URL',
        ];
    }

    /**
     * пполучение таблицы excel
     * @return ExcelCreate
     */
    private function getExcel()
    {
        return new ExcelCreate($this->arParams['HL_ID']);
    }

    /**
     * помещаем экземпляр сущности в свойство
     *
     * @param $emtity
     * @param $hlbId
     */
    private function setEntity($entity, $hlbId)
    {
        $this->entity = $entity->compileEntity($hlbId);
    }

    /**
     * Получаем количество страниц
     *
     * @return array|null
     */
    private function navigation()
    {
        $count = ($this->entity)::getCount();

        if ($count > $this->arParams['ELEMENT_COUNT']) {
            $maxPage = intval($count / $this->arParams['ELEMENT_COUNT']) + 1;
            for ($i=1; $i<=$maxPage; $i++) {
                    $pages[] = $i;
            }
            return $pages;
        }
    }

    /**
     * Получение элементов
     *
     * @return array
     */
    private function getBookMarkList():array
    {
        return ($this->entity)::getList([
            'filter' => $this->arFilter,
            'limit'=> $this->arParams['ELEMENT_COUNT'],
            'offset' => $this->offset,
            'order' => [$this->order['BY']=>$this->order['ORDER']],
            'select' => ['*'],
        ])->fetchAll();

    }

}
?>