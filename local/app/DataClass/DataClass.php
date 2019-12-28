<?php

namespace Custom\DataClass;
/**
 *
 * HLB и таблицы можно работать с ORM
 * с версии Битрикса 19.1 появилась возможность для ИнфоБлоков
 * Interface DataClass
 * @package Custom\DataClass
 */
interface DataClass
{
    /**
     * @param int $hlBlockId
     * @return string
     */
    public function compileEntity(int $hlBlockId): string;
}

?>