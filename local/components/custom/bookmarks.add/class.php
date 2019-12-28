<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Application,
    Bitrix\Main\Localization\Loc,
    Vincepare\FaviconDownloader\FaviconDownloader,
    Custom\DataClass\HighLoadBlock,
    Bitrix\Main\Type\DateTime;

class BookMarksList extends \CBitrixComponent
{
    /**
     * сущность
     * @var object
     */
    private $entity;

    /**
     * id элемента
     * @var int
     */
    private $id = 0;

    /**
     *url
     * @var string
     */
    private $url = '';

    /**
     * Данные для закладки
     *
     * @var array
     */
    private $bookMarksData = [];

    /**
     * Подготовка компонента метод наследуется из /CBitrixComponent
     * @param $arParams
     * @return array
     */
    public function onPrepareComponentParams($arParams)
    {
        return $result = [
            "IB_CODE" => $arParams['IB_CODE'],
            "HL_ID" => $arParams['HL_ID'],
            "FIELD_TYPE" => $arParams['FIELD_TYPE'],
        ];
    }


    /**
     *
     * Выполнение компонента
     * @return null|void
     */
    public function executeComponent()
    {
        if ($this->startResultCache()) {

            try {

                $request = Application::getInstance()
                    ->getContext()
                    ->getRequest();

                $this->url = $request->get('url');

                if ($this->url) {

                    // В данном примере исполльзую HLB, но возможно исполльзовать
                    // ИнфоБлоки и таблицы(если для них создана ORM сущность)
                    // В параметрах компонента можно будет реализовать запись в ИБ
                    // или в таблицу
                    $this->setEntity(new HighLoadBlock(), $this->arParams['HL_ID']);


                    if (!$this->entity) {
                        throw new Exception(Loc::getMessage('0x0000'));
                    }

                    if (!$this->checkProtocol()) {
                        throw new Exception(Loc::getMessage("0x0005"));
                    }

                    if ($this->checkRow()) {
                        throw new Exception(Loc::getMessage("0x0001"));
                    }

                    if (is_string($this->checkConnection())) {
                        throw new Exception($this->checkConnection());
                    }

                    if (!$this->getMeta()) {
                        throw new Exception(Loc::getMessage("0x0003"));
                    }

                    if (!$this->getFavicon()) {
                        throw new Exception(Loc::getMessage("0x0004"));
                    }

                    if ($request->get('delete_code')) {
                        $this->setDeleteCode($request->get('delete_code'));
                    }

                    if (!is_null($this->setNewBookMark())) {
                        exit("<meta http-equiv='refresh' content='0; url= ?ID=" . $this->id . "'>");
                    } else {
                        throw new Exception(Loc::getMessage("0x0006"));
                    }
                }

            } catch (Exception $exception) {
                $this->arResult['ERRORS'] = $exception->getMessage();
            }


            $this->IncludeComponentTemplate();
        }
    }

    /**
     * @param $code
     */
    private function setDeleteCode(string $code): void
    {
        $this->bookMarksData['DELETE_CODE'] = md5($code);
    }


    /**
     * помещаем экземпляр сущности в свойство
     *
     * @param $emtity
     * @param $hlbId
     */
    private function setEntity($entity, $hlbId) {
        $this->entity = $entity->compileEntity((int)$hlbId);
    }

    /**
     *
     * проверка http/https
     * @return bool|null
     */
    private function checkProtocol(string $protocol = 'https')
    {
        if (!stristr($this->url, 'http')) {

            $url = "{$protocol}://" . $this->url;
            $headers = get_headers($url);

            if (!$headers || stristr($headers[0], "301")) {
                $this->checkProtocol('http');
            }

            if (!$headers) {
                return false;
            }

            $this->url = $url;
            return true;
        }
        return true;
    }


    /**
     * проверка ссылки на существование
     * @return bool|null
     */
    private function checkRow()
    {
        $id = $this->entity::getList([
            'filter' => [
                'UF_URL' => $this->url,
            ],
            'select' => ['ID']
        ])->fetch();

        if ($id) {
            return true;
        }
    }

    /**
     * Пройверка связи с сайтом
     * Получение ошибки
     * @return bool|string
     */
    private function checkConnection()
    {

        $header = get_headers($this->url);

        if ($header) {
            $header = $header[0];

            if (stristr($header, "200")) {
                $header = true;
            }

            return $header;
        }

        return Loc::getMessage('0x0007');

    }

    /**
     * получение:
     * descsription;
     * keywords;
     *
     * @return bool
     */
    private function getMeta():bool
    {
        $metaTags =  get_meta_tags($this->url);

        if ($metaTags) {
            $this->bookMarksData = [
                'TITLE' => ($metaTags['hostname'])?$metaTags['hostname']:$this->getTitle(),
                'DESC' => ($metaTags['description'])?$metaTags['description']:'',
                'KEYS' => ($metaTags['keywords'])?$metaTags['keywords']:'',
            ];

            return true;
        }
        return false;
    }

    /**
     * Если title не заложен в meta
     * Парсим контент
     *
     * @return string
     */
    private function getTitle(): string
    {
        $pageContent = file_get_contents ($this->url);

        preg_match_all(
            "|<title>(.*)</title>|sUSi",
            $pageContent,
            $title
        );

        if ($title[1][0]) {
            return $title[1][0];
        } else {
            return '';
        }
    }

    /**
     * Получение favicon
     * Сохранение и преобразование в массив для запись в БД Битрикс
     *
     * @return bool|null
     */
    private function getFavicon()
    {
        $favicon = new FaviconDownloader($this->url);

        if (!file_exists($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'upload/favico')) {
            mkdir($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'upload/favico', 0775);
        }

        if ($favicon->icoExists) {
            $filename = Application::getDocumentRoot() . DIRECTORY_SEPARATOR
                . 'upload' . DIRECTORY_SEPARATOR
                . 'favico' . DIRECTORY_SEPARATOR
                . 'fav-' . time() . '.' . 'png';

            $file = file_put_contents($filename, $favicon->icoData);

            if ($file) {
                $this->bookMarksData['ICO'] = CFile::MakeFileArray($filename);

                return true;
            }
        }
    }

    /**
     * Сохраняем элемент в таблице
     *
     * @param $entity
     * @return integer|null
     */
    private function setNewBookMark()
    {
       $result = ($this->entity)::add([
            'UF_TITLE' => $this->bookMarksData['TITLE'],
            'UF_URL' => $this->url,
            'UF_FAVICO' => $this->bookMarksData['ICO'],
            'UF_CREATED_AT' => DateTime::createFromTimestamp(time()),
            'UF_META_DESC' => $this->bookMarksData['DESC'],
            'UF_META_KEY' => $this->bookMarksData['KEYS'],
            'UF_DELETE_CODE' => $this->bookMarksData['DELETE_CODE'] ?? '',
        ]);

       if ($result->isSuccess()) {
           return $this->id = $result->getId();
       }
    }

}

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_after.php");


?>