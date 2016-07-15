<?php

namespace Laralib\L5scaffold\Validators;

/**
 * Class SchemaParser
 * @package Laralib\L5scaffold\Migrations
 * @author Ryan Gurnick <ryangurnick@gmail.com>
 */
class SchemaParser
{

    /**
     * The parsed schema.
     *
     * @var array
     */
    private $schema = [];

    /**
     * Parse the command line migration schema.
     * Ex: name:string, age:integer:nullable
     *
     * @param  string $schema
     * @return array
     */
    public function parse($schema)
    {
        $fields = $this->splitIntoFields($schema);

        foreach ($fields as $field) {
            $segments = $this->parseSegments($field);

            $this->addField($segments);
        }

        return $this->schema;
    }

    /**
     * Add a field to the schema array.
     *
     * @param  array $field
     * @return $this
     */
    private function addField($field)
    {
        $this->schema[] = $field;

        return $this;
    }

    /**
     * Get an array of fields from the given schema.
     *
     * @param  string $schema
     * @return array
     */
    private function splitIntoFields($schema)
    {
        return preg_split('/,\s?(?![^()]*\))/', $schema);
    }

    /**
     * Get the segments of the schema field.
     *
     * @param  string $field
     * @return array
     */
    private function parseSegments($field)
    {
        $segments = explode(':', $field);

        $name = array_shift($segments);
        $arguments = [];

        // Do we have arguments being used here?
        // Like: string(100)
        if(isset($segments[0]) && $segments[0] != null) {
            if (preg_match('/(.+?)\(([^)]+)\)/', $segments[0], $matches)) {
                $arguments[$matches[1]] = $matches[2];
            } else {
                $arguments[$segments[0]] = '';
            }
        }
        return compact('name', 'arguments');
    }

}

