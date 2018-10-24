<?php

namespace App\Services\Support;

use App\Services\Support\StaticDBData\Exceptions\StaticDBDataInvalidModelException;
use App\Services\Support\StaticDBData\Exceptions\StaticDBDataNotFoundException;
use Illuminate\Database\Eloquent\Model;

abstract class StaticDBData
{
    protected $data = [];

    protected $modelClass;

    public function data(): array
    {
        return $this->data;
    }

    public function idToNameOrFail(int $id): string
    {
        $name = $this->idToName($id);

        if (!$name) {
            throw new StaticDBDataNotFoundException(static::class, $id, 'id');
        }

        return $name;
    }

    public function nameToIdOrFail(string $name): int
    {
        $id = $this->nameToId($name);

        if (!$id) {
            throw new StaticDBDataNotFoundException(static::class, $name, 'name');
        }

        return $id;
    }

    /**
     * @param int|string|Model $value
     * @return int
     * @throws StaticDBDataNotFoundException
     */
    public function toIdOrFail($value): int
    {
        $id = $this->toId($value);

        if (!$id || !is_int($id)) {
            if ($this->isModel($value)) {
                throw new StaticDBDataInvalidModelException(static::class, $value);
            }
            throw new StaticDBDataNotFoundException(static::class, $value);
        }

        return $id;
    }

    public function idToName(int $id): ?string
    {
        return array_get($this->data(), $id . '.name');
    }

    public function idToDisplayName(int $id): ?string
    {
        return array_get($this->data(), $id . '.display_name');
    }

    public function idToDisplayNameOrFail(int $id): string
    {
        $name = $this->idToDisplayName($id);

        if (!$name) {
            throw new StaticDBDataNotFoundException(static::class, $name, 'display_name');
        }

        return $name;
    }

    public function nameToId(string $name): ?int
    {
        $value = $this->byName($name);
        return array_get($value, 'id');
    }

    protected function byName(string $name): ?array
    {
        return collect($this->data())
            ->firstWhere('name', '=', $name);
    }

    /**
     * @param string|int|Model $value
     * @return int|null
     */
    public function toId($value)
    {
        $valueId = $value;

        if (is_string($value)) {
            if (is_numeric($value)) {
                $valueId = (int)$valueId;
            } else {
                $valueId = $this->nameToId($value);
            }
        }

        if ($this->isModel($value)) {
            $valueId = $value->id;
        }

        if (!$this->idExists($valueId)) {
            $valueId = false;
        }
        return $valueId;
    }

    public function toDropDownOptions(): array
    {
        return collect($this->data())->pluck('display_name', 'id')->toArray();
    }

    public function idExists($id): bool
    {
        if (is_string($id) || is_int($id)) {
            return array_key_exists($id, $this->data);
        }
        return false;
    }

    protected function convertToModel($value)
    {
        $id = $this->toId($value);

        $model = new $this->modelClass;

        $attributes = $this->data[$id];
        $model->forceFill($attributes);

        return $model;
    }

    protected function isModel($value)
    {
        return $value instanceof $this->modelClass;
    }
}
