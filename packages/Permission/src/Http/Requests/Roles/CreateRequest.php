<?php

namespace Packages\Permission\Http\Requests\Roles;

use App\Http\Requests\ValidatorBase;

class CreateRequest extends ValidatorBase
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name'  => 'required|unique:roles,name|max:255',
        ];
    }
}
