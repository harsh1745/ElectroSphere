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
            'name' => ['required', 'string', 'max:191'], 
            'category_id' => ['nullable', 'exists:categories,id'],
            'price' => ['required', 'numeric', 'min:0.01', 'regex:/^\d+(\.\d{1,2})?$/'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'max:2048'],
        ];
    }
}
