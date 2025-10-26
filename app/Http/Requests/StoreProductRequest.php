<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize()
    {
        // Admin side form hai, toh yahaan admin check kar sakte ho (e.g., auth()->guard('admin')->check())
        return true;
    }

    public function rules()
    {
        return [
            // Database schema se derived rules:
            'name' => ['required', 'string', 'max:191'], // Null: No
            'category_id' => ['nullable', 'exists:categories,id'], // Null: Yes, check if category exists
            'price' => ['required', 'numeric', 'min:0.01', 'regex:/^\d+(\.\d{1,2})?$/'], // Null: No, decimal(10,2)
            'description' => ['nullable', 'string'], // Null: Yes
            'image' => ['nullable', 'image', 'max:2048'], // Null: Yes, max 2MB file
        ];
    }
}
