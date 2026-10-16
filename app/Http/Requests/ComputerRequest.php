<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ComputerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // При создании $computerId равен null, при обновлении берется ID редактируемой модели
        $computerId = $this->route('computer') instanceof \App\Models\Computer
            ? $this->route('computer')->id
            : $this->route('computer');

        return [
            'zone_id' => [
                'required',
                'integer',
                'exists:zones,id',
            ],
            'inventory_number' => [
                'required',
                'string',
                'min:4',    // MIN_LEN = 4 (по формуле 2 + (29 mod 3))
                'max:187',  // STR_LEN = 187 (по формуле 100 + 3 * 29)
                Rule::unique('computers', 'inventory_number')->ignore($computerId),
            ],
            'code' => [
                'required',
                'string',
                'regex:/^[A-Z]{3}-[0-9]{3}$/', // CODE_L=3, CODE_D=3 (маска [A-Z]{3}-[0-9]{3})
                Rule::unique('computers', 'code')->ignore($computerId),
            ],
            'description' => [
                'nullable',
                'string',
                'max:2000',
            ],
            'cpu' => [
                'required',
                'string',
                'min:4',
                'max:187',
            ],
            'ram_gb' => [
                'required',
                'integer',
                'min:1',
                'max:512',
            ],
            'hour_price' => [
                'required',
                'numeric',
                'min:0',
                'max:999999.99',
            ],
            'is_vip' => [
                'boolean',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'Поле «:attribute» обязательно для заполнения.',
            'inventory_number.min' => 'Инвентарный номер должен содержать не менее :min символов (MIN_LEN = 4).',
            'inventory_number.max' => 'Инвентарный номер не должен превышать :max символов (STR_LEN = 187).',
            'inventory_number.unique' => 'Компьютер с таким инвентарным номером уже существует.',
            'code.regex' => 'Код должен строго соответствовать формату AAA-999 (3 заглавные латинские буквы, дефис, 3 цифры).',
            'code.unique' => 'Компьютер с таким кодом уже зарегистрирован.',
            'zone_id.exists' => 'Выбранная игровая зона не найдена в базе данных.',
            'ram_gb.min' => 'Объем оперативной памяти должен быть не менее :min ГБ.',
            'ram_gb.max' => 'Объем оперативной памяти не может превышать :max ГБ.',
            'hour_price.min' => 'Стоимость часа аренды не может быть отрицательной.',
        ];
    }

    public function attributes(): array
    {
        return [
            'zone_id' => 'Зона клуба',
            'inventory_number' => 'Инвентарный номер',
            'code' => 'Код',
            'cpu' => 'Процессор',
            'ram_gb' => 'Объем ОЗУ',
            'hour_price' => 'Цена за час',
            'description' => 'Описание',
            'is_vip' => 'VIP статус',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_vip' => $this->boolean('is_vip'),
        ]);
    }
}
