<?php


namespace Regnerisch\Airtable;


use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\GuzzleException;

class Client
{
    private $apiKey;

    /** @var HttpClient */
    protected $client;

    protected $base;

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
            'query' => []
        ]);

        return Records::fromApi($records);
    }

    public function record(string $table, string $record): Record
    {
        $record = $this->request('GET', $table . '/' . $record);

        return Record::fromApi($record);
    }

    public function create(string $table, $record): Records
    {
        if ($record instanceof Record) {
            $recs = new Records([$record]);
        } else if ($record instanceof Records) {
            $recs = $record;
        } else {
            // TODO: throw new TypeError
        }

        $records = $this->request('POST', $table, [
            'json' => [
                'records' => $recs->map(static function (Record $rec) {
                    return $rec->toArray();
                }),
            ],
        ]);

        return Records::fromApi($records);
    }

    public function update(string $table, $record): Records
    {
        if ($record instanceof Record) {
            $recs = new Records([$record]);
        } else if ($record instanceof Records) {
            $recs = $record;
        } else {
            // TODO: throw new TypeError
        }

        $records = $this->request('PATCH', $table, [
            'json' => [
                'records' => $recs->map(static function (Record $rec) {
                    return $rec->toArray();
                }),
            ],
        ]);

        return Records::fromApi($records);
    }

    public function delete(string $table, $record)
    {
        if ($record instanceof Record) {
            $recs = new Records([$record]);
        } else if ($record instanceof Records) {
            $recs = $record;
        } else {
            // TODO: throw new TypeError
        }

        $records = $this->request('DELETE', $table, [
            'json' => [
                'records' => $recs->map(static function (Record $rec) {
                    return $rec->id();
                })
            ],
        ]);

        return Records::fromApi($records);
    }

    /**
     * @param string $method
     * @param string $uri
     * @param array $options
     * @return mixed
     * @throws GuzzleException
     * @throws \JsonException
     */
    private function request(string $method, string $uri = '', array $options = [])
    {
        if (!$this->base) {
            // TODO: Throw InvalidBaseException
        }

        $response = $this->client->request($method, $uri, $options);

        return json_decode($response->getBody()->getContents(), false, 512, JSON_THROW_ON_ERROR);
    }

    private function client(): HttpClient
    {
        return new HttpClient([
            'base_uri' => 'https://api.airtable.com/v0/' . $this->base . '/',
            'auth' => [
                'Authorization' => 'Bearer ' . $this->apiKey,
            ]
        ]);
    }
}
