<?php


namespace Regnerisch\Tests;


use PHPUnit\Framework\TestCase;
use Regnerisch\Airtable\Fields;

/**
 * @coversDefaultClass \Regnerisch\Airtable\Fields
 */
class FieldsTest extends TestCase
{
    /**
     * @covers ::__get()
     */
    public function test__get()
    {
        $fields = new Fields([
            'Property 1' => 'Value 1',
            'Property 2' => 'Value 2',
            'Property 3' => true,
            'Property 4' => ['Value 4']
        ]);

        self::assertEquals(
            'Value 1',
            $fields->{'Property 1'}
        );

        self::assertEquals(
            'Value 2',
            $fields->{'Property 2'}
        );

        self::assertEquals(
            true,
            $fields->{'Property 3'}
        );

        self::assertEquals(
            ['Value 4'],
            $fields->{'Property 4'}
        );

        self::assertEquals(
            null,
            $fields->{'Property 5'}
        );
    }

    /**
     * @covers ::__set()
     */
    public function test__set()
    {
        $fields = new Fields([]);

        self::assertEquals(
            [],
            $fields->toArray()
        );

        $fields->NewProperty = 'Value 1';
        $fields->{'Property 2'} = 'Value 2';

        self::assertEquals(
            [
                'NewProperty' => 'Value 1',
                'Property 2' => 'Value 2'
            ],
            $fields->toArray()
        );
    }

    /**
     * @covers ::__isset()
     */
    public function test__isset()
    {
        $fields = new Fields([
            'Property 1' => 'Value 1',
        ]);

        self::assertTrue(
            isset($fields->{'Property 1'})
        );

        self::assertFalse(
            isset($fields->{'Property 2'})
        );
    }
}
