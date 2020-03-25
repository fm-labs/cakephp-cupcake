<?php
declare(strict_types=1);

namespace Banana\Test\TestCase\Model;

use Cake\ORM\TableRegistry;

/**
 * Class CsvTableTest
 * @package Banana\Test\TestCase\Model
 */
class CsvTableTest extends ArrayTableTest
{
    public function setUp(): void
    {
        TableRegistry::getTableLocator()->setConfig('TestCsv', [
            'className' => 'Banana\Test\TestCase\Model\Table\TestCsvTable',
            'file' => dirname(dirname(dirname(__FILE__))) . DS . 'testdata/table.csv',
        ]);
    }

    /**
     * @return CsvTable
     */
    protected function _table()
    {
        return TableRegistry::getTableLocator()->get('TestCsv');
    }
}
