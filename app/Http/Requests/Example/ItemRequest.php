<?php

namespace App\Http\Requests\Example;

use App\Constants\StatusConstant;
use App\Data\Example\ItemData;
use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;

class ItemRequest extends BaseRequest
{
    /**
     * Determine if the authenticated user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'status' => [
                'nullable',
                'string',
                Rule::in(StatusConstant::getAllConstantValues()),
            ],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages(): array
    {
        $statuses = implode(', ', StatusConstant::getAllConstantValues());

        return [
            'name.required' => 'The name is required.',
            'name.string' => 'The name must be a valid string.',
            'name.max' => 'The name must not exceed :max characters.',
            'status.string' => 'The status must be a valid string.',
            'status.in' => 'Invalid status value. Allowed values are: ' . $statuses . '.',
        ];
    }

    /**
     * Transform request to data object.
     *
     * @return ItemData
     */
    public function toData(): ItemData
    {
        return ItemData::from([
            'name' => $this->getInputAsString('name'),
            'status' => $this->getInputAsString('status', StatusConstant::PUBLISHED),
            'metaData' => $this->getMetaData(),
            'userData' => $this->getAuthUserData(),
        ]);
    }
}
