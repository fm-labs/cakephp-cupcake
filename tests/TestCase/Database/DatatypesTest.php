<?php

namespace Banana\Test\TestCase\Database;

use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

class DatatypesTest extends TestCase
{
    public $fixtures = ['plugin.Banana.Datatypes'];

    /**
     * @var Table
     */
    public $table;

    public function setUp(): void
    {
        $this->table = TableRegistry::getTableLocator()->get('Banana.Datatypes');
        $this->table->getSchema()->setColumnType('json', 'json');
        $this->table->getSchema()->setColumnType('serialized', 'serialize');
        //$this->table->getSchema()->setColumnType('base64', 'base64');
    }

    public function testJsonDataType()
    {
        $data = ['Hello' => 'World'];
        $entity = $this->table->newEntity();

        // write
        $entity->json = $data;
        $entity = $this->table->save($entity);
        $this->assertEquals($data, $entity->json);

        // read
        $entity = $this->table->get($entity->id);
        $this->assertEquals($data, $entity->json);
    }

    public function testSerializedDataType()
    {
        $data = ['Hello' => 'World'];
        $entity = $this->table->newEntity();

        // write
        $entity->serialized = $data;
        $entity = $this->table->save($entity);
        $this->assertEquals($data, $entity->serialized);

        // read
        $entity = $this->table->get($entity->id);
        $this->assertEquals($data, $entity->serialized);
    }

    public function tearDown(): void
    {
        unset($this->table);
        TableRegistry::getTableLocator()->clear();
    }
}
