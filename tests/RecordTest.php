<?php

declare(strict_types=1);

namespace Regnerisch\Tests;

use PHPUnit\Framework\TestCase;
use Regnerisch\Airtable\Record;

/**
 * @coversDefaultClass \Regnerisch\Airtable\Record
 */
class RecordTest extends TestCase
{
    /**
     * @covers ::__construct()
     */
    public function test__constructWithoutId()
    {
        $record = new Record([
            'Property 1' => 'Value 1',
        ]);

        self::assertInstanceOf(
            Record::class,
            $record
        );

        self::assertEquals(
            [
                'Property 1' => 'Value 1',
            ],
            $record->fields()->toArray()
        );

        self::assertNull(
            $record->id()
        );
    }

    /**
     * @covers ::__construct()
     */
    public function test__constructWithId()
    {
        $record = new Record([
            'Property 1' => 'Value 1',
        ], 'id');

        self::assertInstanceOf(
            Record::class,
            $record
        );

        self::assertEquals(
            [
                'Property 1' => 'Value 1',
            ],
            $record->fields()->toArray()
        );

        self::assertEquals(
            'id',
            $record->id()
        );
    }

    /**
     * @covers ::fromApi()
     */
    public function testFromApi()
    {
        $record = Record::fromApi(new class() extends \stdClass {
            public function __construct()
            {
                $this->id = 'id';
                $this->fields = new class() extends \stdClass {
                    public $Property1 = 'Value 1';
                };
            }
        });

        self::assertEquals(
            [
                'record' => 'id',
                'fields' => [
                    'Property1' => 'Value 1',
                ],
            ],
            $record->toArray()
        );
    }
}
