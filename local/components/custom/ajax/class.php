<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Engine\ActionFilter;
use Bitrix\Main\Security\Authentication;
use Custom\DataClass\HighLoadBlock;


class ajax extends CBitrixComponent implements Controllerable
{

    public function configureActions()
    {
        return [
            'method' => [
                '-prefilters' => [
                    Authentication::class,
                ],
            ],
        ];
    }

    function executeComponent()
    {
        $this->includeComponentTemplate();
    }

    /**
     * @param $code
     * @param $hlbId
     * @param $elementId
     * @return array
     */
    public function deleteElementAction($code, $hlbId, $elementId)
    {
       $result = false;

        if ($code) {
            $code = md5($code);
            $book_marks = new HighLoadBlock();
            $bookMarksEntity = $book_marks->compileEntity($hlbId);
            $deleteCode = $bookMarksEntity::getList([
                'filter' => ['ID'=>$elementId],
                'select' => ['UF_DELETE_CODE']
            ])->fetch();

            if ($code === $deleteCode['UF_DELETE_CODE']) {
                $result = $bookMarksEntity::delete($elementId);
                $result = $result->isSuccess();
            }

        }

        return [
            'result' => $result
        ];
    }
}