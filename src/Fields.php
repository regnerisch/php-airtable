<?php


namespace Regnerisch\Airtable;


class Fields
{
    protected $fields;

    /**
     * Fields constructor.
     * @param array|\stdClass $fields
     */
    public function __construct($fields)
    {
        $this->fields = (array) $fields;
    }

    public function __get($name)
    {
        if (!property_exists($this->fields, $name)) {
            return null;
        }

        return $this->fields->{$name};
    }

    public function __set($name, $value)
    {
        $this->fields->{$name} = $value;
    }

    public function __isset($name)
    {
        return property_exists($this->fields, $name);
    }

    public function toArray(): array
    {
        return $this->fields;
    }
}
