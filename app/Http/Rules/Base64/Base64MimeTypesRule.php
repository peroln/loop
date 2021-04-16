<?php

declare(strict_types=1);

namespace App\Http\Rules\Base64;

use App\Exceptions\Application\ApplicationException;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Arr;

class Base64MimeTypesRule implements Rule
{
    private array $onlyMimeTypes;

    private string $mimeType;

    /**
     * Base64MimeTypes constructor.
     *
     * @param array|string $onlyMimeTypes
     *
     * @throws ApplicationException
     */
    public function __construct($onlyMimeTypes)
    {
        if (is_string($onlyMimeTypes)) {
            $this->onlyMimeTypes = explode(',', $onlyMimeTypes);
        } elseif (is_array($onlyMimeTypes)) {
            $this->onlyMimeTypes = $onlyMimeTypes;
        } else {
            throw new ApplicationException("Invalid mime types list");
        }
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed  $value
     *
     * @return bool
     * @throws \Exception
     */
    public function passes($attribute, $value): bool
    {
        $parts = explode(";base64,", $value);
        $this->mimeType = Arr::last(explode("/", $parts[0]));

        return in_array($this->mimeType, $this->onlyMimeTypes, true);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return sprintf(
            "Invalid mime type, current mime type %s does not located in available list %s",
            $this->mimeType,
            implode(',', $this->onlyMimeTypes)
        );
    }
}
