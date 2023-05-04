<?php

declare(strict_types=1);

namespace Medas\JsonStorage;

use Medas\JsonStorage\Actions\JsonInsertValuesAction;
use Medas\JsonStorage\Definitions\{Definition, ScalarType};
use Medas\JsonStorage\Exceptions\{IdGivenInParameterAndData, StringIdNotGiven, UnknownField};
use Medas\JsonStorage\Records\{JsonRecord, JsonRecordSet};
use Medas\ObjectToArraySerializer\{ArrayToObjectCaster, ObjectToArrayCaster};
use Medas\StorageManager\Interfaces\{Storage, Store};
use Medas\StorageManager\UnitOfWork\Action;

class JsonFile implements Store
{
    private Definition $definition;
    private mixed $records;
    private ObjectToArrayCaster $objectToArrayCaster;
    private ArrayToObjectCaster $arrayToObjectCaster;

    public function __construct(
        private readonly JsonDirectory $directory,
        private readonly string        $name,
    )
    {
        $this->objectToArrayCaster = service(ObjectToArrayCaster::class);
        $this->arrayToObjectCaster = service(ArrayToObjectCaster::class);

        $this->assertFile();
    }

    private function assertFile(): void
    {
        if (!file_exists($this->filename())) {
            $this->definition = new Definition();
            $this->records = [];
            $this->write();
        }
        else {
            $this->read();
        }
    }

    private function filename(): string
    {
        return $this->directory->directory . DIRECTORY_SEPARATOR . $this->name . '.json';
    }

    public function write(): void
    {
        $encoded = json_encode([
            'definition' => $this->objectToArrayCaster->cast($this->definition),
            'records' => array_map(fn(JsonRecord $record) => $record->data(), $this->records),
        ], JSON_NUMERIC_CHECK | JSON_PRETTY_PRINT);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception(json_last_error_msg());
        }

        file_put_contents($this->filename(), $encoded . "\n");
    }

    public function name(): string
    {
        return $this->name;
    }

    public function storage(): Storage
    {
        return $this->directory;
    }

    public function fetchRecord(array $filters): JsonRecord|null
    {
        $records = $this->fetchAll($filters);

        return array_pop($records);
    }

    public function fetchAll(array $filters): array|null
    {
        if (!$filters) {
            return $this->records;
        }

        $filtered = [];

        foreach ($this->records as $id => $record) {
            foreach ($filters as $key => $value) {
                if ($record[$key] !== $value) {
                    continue 2;
                }
            }

            $filtered[$id] = $record;
        }

        return $filtered;
    }

    public function read(): void
    {
        $data = json_decode(file_get_contents($this->filename()), true);

        $this->definition = $this->arrayToObjectCaster->cast(
            $data['definition'],
            Definition::class
        );

        $this->records = array_map(
            fn(mixed $id, array $data) => new JsonRecord($data, $id),
            array_keys($data['records']),
            array_values($data['records']),
        );
    }

    public function prepareCreate(array $values): Action
    {
        $id = null;

        if (array_key_exists($this->definition->idName, $values)) {
            $id = $values[$this->definition->idName];
            unset($values[$this->definition->idName]);
        }

        return new JsonInsertValuesAction($this->directory, $this, $id, $values);
    }

    public function prepareGet(array $filters): Action
    {
        // TODO: Implement prepareGet() method.
    }

    public function prepareUpdate(array $updates, array $conditions): Action
    {
        // TODO: Implement prepareUpdate() method.
    }

    public function prepareDelete(array $conditions)
    {
        // TODO: Implement prepareDelete() method.
    }

    public function exists(): bool
    {
        return file_exists($this->filename());
    }

    public function insertValues(array $values, mixed $id = null): JsonRecordSet
    {
        $this->determineId($values, $id);
        $this->checkFields($values);
        $jsonRecord = new JsonRecord($values, $id);

        $this->records[$id] = $jsonRecord;
        $this->write();

        return new JsonRecordSet([$jsonRecord]);
    }

    private function determineId(array &$values, mixed &$id): void
    {
        if (array_key_exists($this->definition->idName, $values)) {
            if ($id === null) {
                $id = $values[$this->definition->idName];
                unset($values[$this->definition->idName]);
            }
            else {
                throw new IdGivenInParameterAndData($values[$this->definition->idName], $id);
            }
        }

        switch ($this->definition->idType) {
            case ScalarType::Integer:
                if ($id === null) {
                    $id = $this->records ? max(array_keys($this->records)) + 1 : 1;
                }
                break;
            case ScalarType::String:
                if ($id === null) {
                    throw new StringIdNotGiven($this);
                }
        }
    }

    private function checkFields(array $values): void
    {
        foreach ($values as $field => $value) {
            if (!array_key_exists($field, $this->definition->fields)) {
                throw new UnknownField($this, $field);
            }
        }
    }

    public function addField(Definitions\Field $field): void
    {
        $this->definition->fields[$field->name] = $field;
        $this->write();
    }

    public function definition(): Definition
    {
        return $this->definition;
    }
}
