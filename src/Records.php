<?php


namespace Regnerisch\Airtable;


class Records
{
    protected $records = [];

    public function __construct(iterable $records)
    {
        foreach ($records as $record) {
            if (!$record instanceof Record) {
                // TODO: Throw InvalidArgumentException
            }
        }

        $this->records = $records;
    }

    public static function fromApi(array $records): self
    {
        $data = [];
        foreach ($records as $record) {
            $data[] = Record::fromApi($record);
        }

        $instance = new self([]);
        $instance->records = $data;

        return $instance;
    }

    public function toApi(): array
    {
        return array_map(static function (Record $record) {
            return $record->toApi();
        }, $this->records);
    }
}
