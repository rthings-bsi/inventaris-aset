<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAssetRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $assetId = $this->route('asset')->id_assets;
        
        return [
            'asset_code' => 'required|unique:assets,asset_code,' . $assetId . ',id_assets',
            'asset_name' => 'required|max:255',
            'description' => 'nullable',
            'id_categories' => 'required|exists:categories,id_categories',
            'acquisition_cost' => 'required|numeric|min:0',
            'acquisition_date' => 'required|date',
            'condition' => 'required',
            'id_locations' => 'required|exists:locations,id_locations',
            'id_users' => 'nullable|exists:users,id_users',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|in:active,maintenance,broken,disposed'
        ];
    }
}
