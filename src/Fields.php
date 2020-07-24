<?php


namespace Regnerisch\Airtable;


class Fields extends \ArrayIterator
{
    protected $fields;

    /**
     * Fields constructor.
     * @param array|\stdClass $fields
     * @param int $flags
     */
    public function __construct($fields)
    {
        $this->fields = (array) $fields;

        parent::__construct($fields);
    }

    public function __get($name)
    {
        if (!array_key_exists($name, $this->fields)) {
            return null;
        }

        return $this->fields[$name];
    }

    public function __set($name, $value)
    {
        $this->fields[$name] = $value;
    }

    public function __isset($name)
    {
        return array_key_exists($name, $this->fields);
    }

    public function toArray(): array
    {
        return $this->fields;
    }
}
