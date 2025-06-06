<?php

namespace Packages\Upload\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UploadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'file' => [
                'required',
                'image',
                'file_extension:' . config('upload.ALLOWED_TYPE_IMAGES_EXT'),
                'mimes:' . config('upload.ALLOWED_TYPE_IMAGES_EXT'),
                'mimetypes:' . config('upload.ALLOWED_TYPE_IMAGES_MIN'),
                'max:25000',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'Trường :attribute là bắt buộc.',
            'image' => 'Trường :attribute phải là một hình ảnh.',
            'mimes' => 'Trường :attribute phải có định dạng: :values.',
            'mimetypes' => 'Trường :attribute phải có loại MIME: :values.',
            'max' => [
                'file' => 'Dung lượng file không được lớn hơn :max kilobytes.',
            ],
            'file_extension' => 'Trường :attribute không có định dạng hợp lệ.',
        ];
    }

    /**
     * @param  Validator  $validator
     * @return mixed
     */
    protected function failedValidation(Validator $validator): mixed
    {
        throw new HttpResponseException(
            response()->json([
                'error' => true,
                'message' => $validator->errors()->first(),
            ], 422)
        );
    }
}
