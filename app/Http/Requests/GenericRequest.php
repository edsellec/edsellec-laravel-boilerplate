<?php

namespace App\Http\Requests;

use App\Data\Shared\GenericData;

class GenericRequest extends BaseRequest {

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        return [];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages() {
        return [];
    }

    /**
     * Transform request to data transfer object.
     * 
     * @return GenericData
     */
    public function toData(): GenericData
    {
        return GenericData::from([
            'metaData' => $this->getMetaData(),
            'userData' => $this->getAuthUserData(),
        ]);
    }
}
