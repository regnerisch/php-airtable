<?php


namespace Regnerisch\Tests;


use PHPUnit\Framework\TestCase;
use Regnerisch\Airtable\Record;
use Regnerisch\Airtable\Records;

/**
 * @coversDefaultClass \Regnerisch\Airtable\Records
 */
class RecordsTest extends TestCase
{
    /**
     * @covers ::__construct()
     */
    public function test__constructWithInvalidRecords()
    {
        $this->expectException(\TypeError::class);
        $records = new Records([
            new Record([]),
            'Record'
        ]);
    }

    /**
     * @covers ::__construct()
     */
    public function test__constructWithValidRecords()
    {
        $records = new Records([
            new Record([]),
            new Record([]),
        ]);

        self::assertInstanceOf(
            Records::class,
            $records
        );
    }

    /**
     * @covers ::fromApi()
     */
    public function testFromApi()
    {
        $records = Records::fromApi([
            new class {
                public function __construct()
                {
                    $this->id = 'id1';
                    $this->fields = new class {
                        public $Property1 = 'Value 1';
                    };
                }
            },
            new class {
                public function __construct()
                {
                    $this->id = 'id2';
                    $this->fields = new class {
                        public $Property2 = 'Value 2';
                    };
                }
            }
        ]);

        self::assertEquals(
            [
                [
                    'record' => 'id1',
                    'fields' => [
                        'Property1' => 'Value 1',
                    ]
                ],
                [
                    'record' => 'id2',
                    'fields' => [
                        'Property2' => 'Value 2'
                    ]
                ]
            ],
            $records->toArray()
        );
    }
}
