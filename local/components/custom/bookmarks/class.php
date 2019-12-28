<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

class BookMarks extends \CBitrixComponent
{
    /**
     * Component Get variable
     *
     * @var array
     */
    private $arComponentVariables = ['HLB_ID', 'ID', 'ADD'];

    /**
     * список переменных для инициализации шаблона
     *
     * @var array
     */
    private $arVariables = [];

    /***
     * страница компонета
     *
     * @var string
     */
    private $componentPage = '';

    public function onPrepareComponentParams($arParams)
    {
        return $result = [
            "IB_CODE" => $arParams['IB_CODE'],
            "HL_ID" => $arParams['HL_ID'],
            "FIELD_TYPE" => $arParams['FIELD_TYPE'],
            "ELEMENT_COUNT" => $arParams['ELEMENT_COUNT'],
        ];
    }

    public function executeComponent()
    {
        if ($this->startResultCache()) {

            $this->initializeComponentPage();
        }

        $this->IncludeComponentTemplate($this->componentPage);
    }


    private function initializeComponentPage(): void
    {

        $arVariableAliases = CComponentEngine::MakeComponentVariableAliases(
            [],
            $this->arParams['VARIABLE_ALIASES']
        );

        CComponentEngine::InitComponentVariables(
            false,
            $this->arComponentVariables,
            $arVariableAliases,
            $this->arVariables
        );

        switch (key($this->arVariables)){
            case "ADD":
                $this->componentPage = 'add';
                break;
            case "ID":
                $this->componentPage = 'element';
                break;
            default:
                $this->componentPage = 'list';
                break;
        }

        $this->arResult = [
            'VARIABLES' => $this->arVariables,
            'ALIASES' => $arVariableAliases,
        ];
    }
}

?>