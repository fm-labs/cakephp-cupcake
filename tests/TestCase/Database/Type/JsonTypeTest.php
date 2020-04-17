<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         3.3.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
namespace Banana\Test\TestCase\Database\Type;

use Banana\Database\Type\JsonType;
use Cake\Database\TypeFactory;
use Cake\TestSuite\TestCase;
use PDepend\Util\Type;
use PDO;

/**
 * Test for the String type.
 *
 * This is a copy of \Cake\Test\TestCase\Database\Type\JsonTypeTest class,
 * thus performing the same tests on own JsonType implementation
 */
class JsonTypeTest extends TestCase
{
    /**
     * @var \Banana\Database\Type\JsonType
     */
    protected $type;

    /**
     * @var \Cake\Database\Driver
     */
    protected $driver;

    /**
     * Setup
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        TypeFactory::map('json', 'Banana\Database\Type\JsonType');
        $this->type = TypeFactory::build('json');
        $this->driver = $this->getMockBuilder('Cake\Database\Driver')->getMock();
    }

    /**
     * Test toPHP
     *
     * @return void
     */
    public function testToPHP()
    {
        $this->assertNull($this->type->toPHP(null, $this->driver));
        $this->assertSame('word', $this->type->toPHP(json_encode('word'), $this->driver));
        $this->assertSame(2.123, $this->type->toPHP(json_encode(2.123), $this->driver));
    }

    /**
     * Test converting json strings to PHP values.
     *
     * @return void
     */
    public function testManyToPHP()
    {
        $values = [
            'a' => null,
            'b' => json_encode([1, 2, 3]),
            'c' => json_encode('123'),
            'd' => json_encode(2.3),
        ];
        $expected = [
            'a' => null,
            'b' => [1, 2, 3],
            'c' => 123,
            'd' => 2.3,
        ];
        $this->assertEquals(
            $expected,
            $this->type->manyToPHP($values, array_keys($values), $this->driver)
        );
    }

    /**
     * Test converting to database format
     *
     * @return void
     */
    public function testToDatabase()
    {
        $this->assertNull($this->type->toDatabase(null, $this->driver));
        $this->assertSame(json_encode('word'), $this->type->toDatabase('word', $this->driver));
        $this->assertSame(json_encode(2.123), $this->type->toDatabase(2.123, $this->driver));
        $this->assertSame(json_encode(['a' => 'b']), $this->type->toDatabase(['a' => 'b'], $this->driver));
    }

    /**
     * Tests that passing an invalid value will throw an exception
     *
     * @return void
     */
    public function testToDatabaseInvalid()
    {
        $this->expectException(\InvalidArgumentException::class);
        $value = fopen(__FILE__, 'r');
        $this->type->toDatabase($value, $this->driver);
    }

    /**
     * Test marshalling
     *
     * @return void
     */
    public function testMarshal()
    {
        $this->assertNull($this->type->marshal(null));
        $this->assertSame('', $this->type->marshal(''));
        $this->assertSame('word', $this->type->marshal('word'));
        $this->assertSame(2.123, $this->type->marshal(2.123));
        $this->assertSame([1, 2, 3], $this->type->marshal([1, 2, 3]));
        $this->assertSame(['a' => 1, 2, 3], $this->type->marshal(['a' => 1, 2, 3]));
    }

    /**
     * Test that the PDO binding type is correct.
     *
     * @return void
     */
    public function testToStatement()
    {
        $this->assertEquals(PDO::PARAM_STR, $this->type->toStatement('', $this->driver));
    }
}
