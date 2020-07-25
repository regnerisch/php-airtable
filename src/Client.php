<?php

declare(strict_types=1);

namespace Regnerisch\Airtable;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\GuzzleException;
use Regnerisch\Airtable\Exceptions\BaseNotSetException;

class Client
{
    private $apiKey;

    /** @var HttpClient */
    protected $client;

    protected $base;

    protected $options = [
        'fields' => null,
        'filterByFormula' => null,
        'maxRecords' => null,
        'pageSize' => null,
        'sort' => null,
        'view' => null,
        'cellFormat' => null,
        'timeZone' => null,
        'userLocale' => null,
    ];

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function useBase(string $base): void
    {
        $this->base = $base;

        $this->client = $this->client();
    }

    public function records(string $table, array $options = []): Records
    {
        $records = $this->request('GET', $table, [
            'query' => array_intersect_key(
                $options,
                $this->options,
            ),
        ]);

        return Records::fromApi($records);
    }

    public function record(string $table, $record): Record
    {
        if ($record instanceof Record) {
            $id = $record->id();
        } elseif (is_string($record)) {
            $id = $record;
        } else {
            throw new \TypeError('');
        }

        $record = $this->request('GET', $table . '/' . $id);

        return Record::fromApi($record);
    }

    public function create(string $table, $record): Records
    {
        $recs = $this->validate($record);

        $records = $this->request('POST', $table, [
            'json' => [
                'records' => $recs->map(static function (Record $rec) {
                    return [
                        'fields' => $rec->fields()->toArray(),
                    ];
                }),
            ],
        ]);

        return Records::fromApi($records);
    }

    public function update(string $table, $record, bool $destructive = false): Records
    {
        $recs = $this->validate($record);

        $records = $this->request($destructive ? 'PUT' : 'PATCH', $table, [
            'json' => [
                'records' => $recs->map(static function (Record $rec) {
                    return [
                        'id' => $rec->id(),
                        'fields' => $rec->fields()->toArray(),
                    ];
                }),
            ],
        ]);

        return Records::fromApi($records);
    }

    public function delete(string $table, $record): Records
    {
        $recs = $this->validate($record);

        $records = $this->request('DELETE', $table, [
            'query' => [
                'records' => $recs->map(static function (Record $rec) {
                    return $rec->id();
                }),
            ],
        ]);

        return Records::fromApi($records);
    }

    protected function validate($record): Records
    {
        if ($record instanceof Records) {
            return $record;
        }

        if ($record instanceof Record) {
            return new Records([$record]);
        }

        if (is_string($record)) {
            return new Records([
                new Record([], $record),
            ]);
        }

        throw new \TypeError('');
    }

    /**
     * @throws GuzzleException
     * @throws \JsonException
     * @throws BaseNotSetException
     *
     * @return mixed
     */
    protected function request(string $method, string $uri = '', array $options = [])
    {
        if (!$this->client) {
            throw new BaseNotSetException('Base not set. Did you call useBase()?');
        }

        $response = $this->client->request($method, $uri, $options);

        return json_decode($response->getBody()->getContents(), false, 512, JSON_THROW_ON_ERROR);
    }

    protected function client(): HttpClient
    {
        return new HttpClient([
            'base_uri' => 'https://api.airtable.com/v0/' . $this->base . '/',
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiKey,
            ],
        ]);
    }
}
