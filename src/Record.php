<?php

declare(strict_types=1);

namespace Regnerisch\Airtable;

class Record
{
    protected $id;

    protected $fields;

    public function __construct($fields = [], ?string $id = null)
    {
        $this->id = $id;
        $this->fields = new Fields($fields);
    }

    public function id(): ?string
    {
        return $this->id;
    }

    public function fields(): Fields
    {
        return $this->fields;
    }

    public static function fromApi(object $record): Record
    {
        return new self($record->fields ?? [], $record->id);
    }

    public function toArray(): array
    {
        return [
            'record' => $this->id,
            'fields' => $this->fields()->toArray(),
        ];
    }
}
