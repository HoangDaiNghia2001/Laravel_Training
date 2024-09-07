<?php

namespace App\Http\Requests;

use App\Response\ApiResponse;
use App\Traits\AuthorizeTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BrandRequest extends FormRequest
{

    use AuthorizeTrait;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->AuthorizeAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(Request $request): array
    {
        return [
            'name' => ['required', 'unique:brands,name,' . $request->id]
        ];
    }
    /**
     * Customize the response format when validation fails.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     */
    protected function failedValidation(Validator $validator)
    {
        $repsonse = new ApiResponse();
        $repsonse->setSuccess(false);
        $repsonse->setMessage("Invalid input data !!!");
        $repsonse->setData($validator->errors());

        throw new HttpResponseException(response()->json($repsonse->toArray(), Response::HTTP_UNPROCESSABLE_ENTITY));
    }
}
