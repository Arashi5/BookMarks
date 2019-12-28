<?php
namespace Custom;

use Ellumilel\ExcelWriter,
    Custom\DataClass\HighLoadBlock;

class ExcelCreate
{
    /**
     * Библиотека для записи в Exel
     *
     * @var ExcelWriter
     */
    private $excelWriter;

    /**
     * Сущность из которой буеруться данные
     *
     * @var object
     */
    private $entity;

    /**
     * массив элементво для записи
     *
     * @var array
     */
    private $arElements = [];

    /**
     *
     * Путь файла на сервере
     *
     * @var string
     */
    private $tempName = '';

    /**
     * Список заголовков
     *
     * @var array
     */
    private $headers = [
        'Title' => 'string',
        'Url' => 'string',
        'Date Create' =>  'DD.MM.YYYY HH:MM:SS',
        'Favicon' => 'string',
        'Description' => 'string',
        'Keywords' => 'string',
    ];

    public function __construct($id)
    {
        $this->excelWriter = new ExcelWriter();
        $this->setEntity(new HighLoadBlock, $id);
        $this->arElements = $this->getElementList();

        if ($this->arElements) {
            $this->excelWrite();
        }
    }

    /**
     * Получаем путь файла на сервере
     *
     * @return string
     */
    public function getTempName()
    {
        return $this->tempName;
    }

    /**
     * Компилируем сущность
     *
     * @param $entity
     * @param $hlbId
     * @return void
     */
    private function setEntity($entity, $hlbId): void
    {
        $this->entity = $entity->compileEntity((int)$hlbId);
    }

    /**
     * Получение списка элементов дял записи
     *
     * @return array
     */
    private function getElementList(): array
    {
        return ($this->entity)::getList([
            'order' => ['UF_CREATED_AT' => 'desc'],
            'select' => ['*']
        ])->fetchAll();
    }

    /**
     * Запись в файл
     * задание заголовка
     * запись строк
     *
     * @return void
     */
    private function excelWrite(): void
    {
        $this->excelWriter->writeSheetHeader('Book Marks', $this->headers);
        $this->excelWriter->setAuthor($_SERVER['SERVER_NAME']);

        foreach ($this->arElements as $element) {
            $this->excelWriter->writeSheetRow('Book Marks',[
                $element['UF_TITLE'],
                $element['UF_URL'],
                ($element['UF_CREATED_AT'])->toString(),
                $_SERVER['SERVER_NAME'] . \CFile::GetPath($element['UF_FAVICO']),
                $element['UF_META_DESC'],
                $element['UF_META_KEY'],
            ]);
        }
       $this->tempName = time();
       $this->tempName = "/upload/favico/{$this->tempName}.xlsx";
       $this->excelWriter->writeToFile($_SERVER['DOCUMENT_ROOT'] . $this->tempName);
    }
}