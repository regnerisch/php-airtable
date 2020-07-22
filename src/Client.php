<?php


namespace Regnerisch\Airtable;


use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\GuzzleException;

class Client
{
    private $apiKey;

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

    public function records($table, array $options = []): Records
    {
        $records = $this->request('GET', '', [
            'query' => []
        ]);

        return Records::fromApi($records);
    }

    public function record(): Record
    {

    }

    public function add(): Records
    {

    }

    public function update(): Records
    {

    }

    public function delete()
    {

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
            'base_uri' => 'https://api.airtable.com',
            'auth' => [
                'Authorization' => 'Bearer ' . $this->apiKey,
            ]
        ]);
    }
}
