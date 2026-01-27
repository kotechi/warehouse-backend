<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePenanggungJawabAsetRequest extends FormRequest
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
        $penanggungJawabAsetId = $this->route('penanggung_jawab_aset');

        return [
            'user_id' => 'nullable|exists:users,id',
            'unit_eselon_ii_id' => 'required|exists:unit_eselon_iis,id',
            'nama_pic' => 'required|string|max:255',
            'nip' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('penanggung_jawab_asets')->ignore($penanggungJawabAsetId)
            ],
            'jabatan' => 'nullable|string|max:150',
            'telepon' => 'nullable|string|max:20',
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('penanggung_jawab_asets')->ignore($penanggungJawabAsetId)
            ],
            'status' => 'required|in:aktif,tidak_aktif',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'user_id.exists' => 'User yang dipilih tidak valid.',
            'unit_eselon_ii_id.required' => 'Unit Eselon II harus dipilih.',
            'unit_eselon_ii_id.exists' => 'Unit Eselon II yang dipilih tidak valid.',
            'nama_pic.required' => 'Nama PIC harus diisi.',
            'nama_pic.max' => 'Nama PIC maksimal 255 karakter.',
            'nip.unique' => 'NIP sudah digunakan oleh penanggung jawab lain.',
            'nip.max' => 'NIP maksimal 50 karakter.',
            'jabatan.max' => 'Jabatan maksimal 150 karakter.',
            'telepon.max' => 'Telepon maksimal 20 karakter.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan oleh penanggung jawab lain.',
            'email.max' => 'Email maksimal 255 karakter.',
            'status.required' => 'Status harus dipilih.',
            'status.in' => 'Status harus aktif atau tidak_aktif.',
        ];
    }
}
