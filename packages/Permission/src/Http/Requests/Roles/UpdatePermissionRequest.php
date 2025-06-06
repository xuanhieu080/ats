<?php

namespace Packages\Permission\Http\Requests\Roles;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePermissionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
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
            'ids'   => 'nullable|array',
            'ids.*' => 'nullable|exists:permissions,name',
        ];
    }

    public function messages(): array
    {
        return [
            'ids.array'   => "Danh sách quyền không phải dạng mảng",
            'ids.*.where' => "Mã quyền không tồn tại",
        ];
    }
}