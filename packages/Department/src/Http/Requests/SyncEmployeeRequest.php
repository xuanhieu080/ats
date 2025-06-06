<?php

namespace Packages\Department\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SyncEmployeeRequest extends FormRequest
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
            'ids' => 'nullable|array',
            'ids.*'      => [
                'required',
                Rule::exists('users', 'id')->where('type','<>', User::TYPE_STUDENT)
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'ids.array'        => "Danh sách nhân viên không phải dạng mảng",
            'ids.*.required' => "Mã nhân viên không được để trống",
            'ids.*.where'    => "Mã nhân viên không tồn tại",
        ];
    }
}