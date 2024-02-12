<?php

namespace App\Http\Requests;

use App\Constants\AppConstant;
use App\Data\Shared\MetaData;
use App\Data\Shared\UserData;
use App\Helpers\DateHelper;
use App\Helpers\ResponseHelper;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class BaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param Validator $validator
     */
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(ResponseHelper::error($validator->errors()->first()));
    }

    /**
     * Transform input value to string.
     *
     * @param string $key
     * @param string|null $defaultValue
     *
     * @return string|null
     */
    public function getInputAsString(string $key, ?string $defaultValue = ''): ?string
    {
        $value = $this->input($key);

        return is_null($value) ? $defaultValue : (string) $value;
    }

    /**
     * Transform input value to int.
     *
     * @param string $key
     * @param int|null $defaultValue
     *
     * @return int|null
     */
    public function getInputAsInt(string $key, ?int $defaultValue = 0): ?int
    {
        $value = $this->input($key);

        return is_null($value) ? $defaultValue : (int) $value;
    }

    /**
     * Transform input value to float.
     *
     * @param string $key
     * @param float|null
     *
     * @return float|null
     */
    public function getInputAsFloat(string $key, ?float $defaultValue = 0.0): ?float
    {
        $value = $this->input($key);

        return is_null($value) ? $defaultValue : (float) $value;
    }

    /**
     * Transform input value to boolean.
     *
     * @param string $key
     * @param bool|null $defaultValue
     *
     * @return bool|null
     */
    public function getInputAsBoolean(string $key, ?bool $defaultValue = false): ?bool
    {
        $value = $this->input($key);

        return is_null($value) ? $defaultValue : filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * Transform input value to array.
     *
     * @param string $key
     * @param array|null $defaultValue
     *
     * @return array|null
     */
    public function getInputAsArray(string $key, ?array $defaultValue = []): ?array
    {
        $value = $this->input($key);

        return is_null($value) ? $defaultValue : (array) $value;
    }

    /**
     * Transform input value to array from a comma separated string.
     *
     * @param string $key
     *
     * @return array|null
     */
    public function getInputAsArrayFromCommaSeparatedString(string $key): ?array
    {
        $value = $this->input($key);

        //Made to is_null due to some string array were passing only 0 string value
        if (is_null($value)) {
            return null;
        }

        return explode(',', (string) $value);
    }

    /**
     * Transform input value (json encoded string) to decoded json.
     *
     * @param string $key
     * @param object|array|null $defaultValue
     *
     * @return object|array|null
     */
    public function getInputAsDecodedJson(string $key, $defaultValue = null)
    {
        $value = $this->input($key);

        return empty($value) ? $defaultValue : json_decode($value);
    }

    /**
     * Transform input value to encoded json.
     *
     * @param string $key
     * @param object|array|null $defaultValue
     *
     * @return string
     */
    public function getInputAsEncodedJson(string $key, $defaultValue = null)
    {
        $value = $this->input($key);

        return empty($value) ? $defaultValue : json_encode($value);
    }

    /**
     * Get carbon datetime from date string.
     *
     * @param string $key
     *
     * @return Carbon|null
     */
    public function getInputAsCarbon(string $key): ?Carbon
    {
        return DateHelper::toCarbon($this->getInputAsString($key));
    }

    /**
     * Transform input value to url.
     *
     * @param string $key
     *
     * @return string
     */
    public function getInputAsUrl(string $key): string
    {
        return urldecode($this->getInputAsString($key));
    }

    /**
     * For pagination. Get the requested page number.
     * @return int
     */
    public function getPage(): int
    {
        return $this->getInputAsInt('page') ?: AppConstant::DEFAULT_PAGE;
    }

    /**
     * For pagination. Get the requested page limit.
     *
     * @param int $maxPageLimit
     *
     * @return int
     */
    public function getPageLimit(int $maxPageLimit = 100): int
    {
        $limit = $this->getInputAsInt('limit') ?: AppConstant::DEFAULT_PAGE_LIMIT;

        return $limit > $maxPageLimit ? AppConstant::DEFAULT_PAGE_LIMIT : $limit;
    }

    /**
     * For pagination. Get the requested page offset.
     * 
     * @return int
     */
    public function getPageOffset(): int
    {
        $offset = $this->getInputAsInt('offset');

        return $offset ?: ($this->getPage() - 1) * $this->getPageLimit();
    }

    /**
     * Get relations for a database query.
     * These are comma separated relations.
     * Example relation1,relation2,relation3
     * 
     * @return array|null
     */
    public function getRelations(): ?array
    {
        $value = $this->input('relations');

        if (!empty($value) && is_string($value) && str_contains($value, '|')) {
            return $this->getRelationsWithColumns();
        }

        $relations = [];

        if (is_null($value)) {
            $relations = null;
        } elseif (is_string($value) && !empty($value)) {
            $relations = explode(',', $value);
        } elseif (is_array($value) && !empty($value)) {
            $relations = $value;
        }

        return $relations;
    }

    /**
     * Get columns for database query.
     * 
     * @return array|null
     */
    public function getColumns(): ?array
    {
        $value = $this->input('columns');
        $columns = [];

        if (is_null($value)) {
            $columns = null;
        } elseif (is_string($value) && !empty($value)) {
            $columns = explode(',', $value);
        } elseif (is_array($value) && !empty($value)) {
            $columns = $value;
        }

        return $columns;
    }

    /**
     * Get relations for a database query.
     * These are pipe separated relations with columns.
     * Example: 'relation1:id,status,date_created|relation2:id,status,date_created|relation3:id,status,date_created'
     * 
     * @return array|null
     */
    public function getRelationsWithColumns(): ?array
    {
        $value = $this->input('relations');
        $relations = [];

        if (is_null($value)) {
            $relations = null;
        } elseif (is_string($value) && !empty($value)) {
            $relations = explode('|', $value);
        } elseif (is_array($value) && !empty($value)) {
            $relations = $value;
        }

        return $relations;
    }

    /**
     * Get request meta data.
     * @return MetaData
     * @throws UnknownProperties
     */
    public function getMetaData(): MetaData
    {
        return MetaData::from([
            'search' => $this->getInputAsString('search'),
            'relations' => $this->getRelations() ?? [],
            'columns' => $this->getColumns() ?? ['*'],
            'groupBy' => $this->getInputAsString('groupBy'),
            'sortBy' => $this->getInputAsString('sortBy', 'id'),
            'sortDirection' => $this->getInputAsString('sortDirection', 'asc'),
            'currentPage' => $this->getPage(),
            'perPage' => $this->getPageLimit(),
            'offset' => $this->getPageOffset(),
            'exact' => $this->getInputAsBoolean('exact')
        ]);
    }

    /**
     * Get authenticated user.
     * 
     * @return UserData
     */
    public function getAuthUserData(): UserData
    {
        return UserData::from([
            'id' => Auth::user()->id ?? null,
        ]);
    }
}
