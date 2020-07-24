<?php


namespace Regnerisch\Airtable;


class Records extends \ArrayIterator
{
    protected $records = [];

    public function __construct(iterable $records)
    {
        foreach ($records as $record) {
            if (!$record instanceof Record) {
                throw new \TypeError(sprintf(
                    'Record passed to %s must be an instance of %s, %s given.',
                    __METHOD__,
                    !is_object($record) ? gettype($record) : get_class($record),
                    Record::class
                ));
            }

            $this->records[$record->id()] = $record;
        }

        parent::__construct($this->records);
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

    public function toArray(): array
    {
        return $this->map(static function (Record $record) {
            return $record->toArray();
        });
    }

    public function map(callable $callback)
    {
        return array_map($callback, $this->records);
    }
}
