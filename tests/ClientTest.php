<?php

declare(strict_types=1);

namespace Regnerisch\Tests;

use PHPUnit\Framework\TestCase;
use Regnerisch\Airtable\Client;
use Regnerisch\Airtable\Record;
use Regnerisch\Airtable\Records;

/**
 * @coversDefaultClass \Regnerisch\Airtable\Client
 */
class ClientTest extends TestCase
{
    /**
     * @covers ::__construct()
     */
    public function test__construct()
    {
        $client = new Client(AIRTABLE_API_KEY);

        self::assertInstanceOf(
            Client::class,
            $client
        );
    }

    /**
     * @covers ::create()
     */
    public function testCreateRecord()
    {
        $client = new Client(AIRTABLE_API_KEY);

        $client->useBase(AIRTABLE_BASE);

        $records = $client->create(AIRTABLE_TABLE, new Record([
            'Name' => 'Created from API',
            'Notes' => 'This is a note!',
        ]));

        $id = $records->toArray()[0]['record'];

        self::assertEquals(
            [
                [
                    'record' => $id,
                    'fields' => [
                        'Name' => 'Created from API',
                        'Notes' => 'This is a note!',
                    ],
                ],
            ],
            $records->toArray()
        );

        return $id;
    }

    /**
     * @covers ::create()
     * @depends testDeleteRecord
     */
    public function testCreateRecords()
    {
        $client = new Client(AIRTABLE_API_KEY);

        $client->useBase(AIRTABLE_BASE);

        $records = $client->create(AIRTABLE_TABLE, new Records([
            new Record([
                'Name' => 'Created from API',
                'Notes' => 'This is a note!',
            ]),
        ]));

        $id = $records->toArray()[0]['record'];

        self::assertEquals(
            [
                [
                    'record' => $id,
                    'fields' => [
                        'Name' => 'Created from API',
                        'Notes' => 'This is a note!',
                    ],
                ],
            ],
            $records->toArray()
        );

        return $id;
    }

    /**
     * @covers ::update()
     * @depends testCreateRecord
     *
     * @param mixed $id
     */
    public function testUpdateRecord($id)
    {
        $client = new Client(AIRTABLE_API_KEY);

        $client->useBase(AIRTABLE_BASE);

        $records = $client->update(AIRTABLE_TABLE, new Records([
            new Record([
                'Name' => 'Updated from API',
            ], $id),
        ]));

        self::assertEquals(
            [
                [
                    'record' => $id,
                    'fields' => [
                        'Name' => 'Updated from API',
                        'Notes' => 'This is a note!',
                    ],
                ],
            ],
            $records->toArray()
        );

        return $id;
    }

    /**
     * @covers ::update()
     * @depends testCreateRecords
     *
     * @param mixed $id
     */
    public function testUpdateRecords($id)
    {
        $client = new Client(AIRTABLE_API_KEY);

        $client->useBase(AIRTABLE_BASE);

        $records = $client->update(AIRTABLE_TABLE, new Records([
            new Record([
                'Name' => 'Updated from API',
            ], $id),
        ]));

        self::assertEquals(
            [
                [
                    'record' => $id,
                    'fields' => [
                        'Name' => 'Updated from API',
                        'Notes' => 'This is a note!',
                    ],
                ],
            ],
            $records->toArray()
        );

        return $id;
    }

    /**
     * @covers ::record()
     * @depends testUpdateRecord
     *
     * @param mixed $id
     */
    public function testRecord($id)
    {
        $client = new Client(AIRTABLE_API_KEY);

        $client->useBase(AIRTABLE_BASE);

        $record = $client->record(AIRTABLE_TABLE, $id);

        self::assertEquals(
            [
                'record' => $id,
                'fields' => [
                    'Name' => 'Updated from API',
                    'Notes' => 'This is a note!',
                ],
            ],
            $record->toArray()
        );

        return $id;
    }

    /**
     * @covers ::records()
     * @depends testUpdateRecords
     *
     * @param mixed $id
     */
    public function testRecords($id)
    {
        $client = new Client(AIRTABLE_API_KEY);

        $client->useBase(AIRTABLE_BASE);

        $records = $client->records(AIRTABLE_TABLE);

        self::assertEquals(
            [
                [
                    'record' => $id,
                    'fields' => [
                        'Name' => 'Updated from API',
                        'Notes' => 'This is a note!',
                    ],
                ],
            ],
            $records->toArray()
        );

        return $id;
    }

    /**
     * @covers ::delete()
     * @depends testRecord
     *
     * @param mixed $id
     */
    public function testDeleteRecord($id)
    {
        $client = new Client(AIRTABLE_API_KEY);

        $client->useBase(AIRTABLE_BASE);

        $records = $client->delete(AIRTABLE_TABLE, new Record([], $id));

        self::assertEquals(
            [
                [
                    'record' => $id,
                    'fields' => [],
                ],
            ],
            $records->toArray()
        );
    }

    /**
     * @covers ::delete()
     * @depends testRecords
     *
     * @param mixed $id
     */
    public function testDeleteRecords($id)
    {
        $client = new Client(AIRTABLE_API_KEY);

        $client->useBase(AIRTABLE_BASE);

        $records = $client->delete(AIRTABLE_TABLE, new Records([
            new Record([], $id),
        ]));

        self::assertEquals(
            [
                [
                    'record' => $id,
                    'fields' => [],
                ],
            ],
            $records->toArray()
        );
    }
}
