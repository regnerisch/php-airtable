<?php


namespace Regnerisch\Airtable;


class Record
{
    protected $id;

    protected $fields;

    public function __construct(array $fields = [], ?string $id = null)
    {
        $this->id = $id;
        $this->fields = new Fields($fields);
    }

    public function id(): string
    {
        return $this->id;
    }

    public function fields(): Fields
    {
        return $this->fields;
    }

    public static function fromApi(\stdClass $record)
    {
        return new self($record->fields, $record->id);
    }

    public function toApi(): array
    {
        return [
            'record' => $this->id,
            'fields' => $this->fields()->toArray()
        ];
    }
}
