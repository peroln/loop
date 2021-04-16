<?php

declare(strict_types=1);

namespace App\Models\Helpers;

use App\Exceptions\ApplicationException;
use App\Models\Pickups;
use Illuminate\Database\Eloquent\Model;

/**
 * Trait ConvertDatesFromFormats
 * @package App\Models\Helpers
 */
trait ConvertDatesFromFormats
{
    /**
     * @return string
     */
    protected function baseSQLDateFormat(): string
    {
        return 'Y-d-m';
    }

    /**
     * @var array
     */
    abstract protected function castsFromDatesOnFill(): array;

    /**
     * @param array $attributes
     *
     * @return Model
     * @throws ApplicationException
     */
    public function fill(array $attributes): Model
    {
        if ($this instanceof Model) {
            foreach ($this->castsFromDatesOnFill() as $field => $fromFormat) {
                if (!empty($attributes) && array_key_exists($field, $attributes)) {
                    $attributes[$field] = convertDateFormat($attributes[$field], $fromFormat);
                }
            }

            return parent::fill($attributes);
        }
        throw new ApplicationException('Current class must to be instance of Model');
    }

    /**
     * @param string|null $value
     *
     * @return string|null
     */
    protected function convertDateAttributeToFormat(?string $value): ?string
    {
        return is_null($value) ? $value : convertDateFormat($value, 'Y-m-d', self::DATE_FORMAT);
    }
}
