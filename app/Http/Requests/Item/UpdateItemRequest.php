<?php

namespace App\Http\Requests\Item;

use App\Enums\ItemStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Override;

class UpdateItemRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'integer', 'min:1', 'max:10000000'],
            'memo' => ['nullable', 'string', 'max:500'],
            'status' => ['required', Rule::enum(ItemStatus::class)],
        ];
    }

    #[Override]
    public function attributes()
    {
        return [
            'name' => '品名',
        ];
    }

    public function name(): string
    {
        return $this->validated('name');
    }

    public function price(): int
    {
        return (int) $this->validated('price');
    }

    public function memo(): ?string
    {
        return $this->validated('memo');
    }

    public function status(): ItemStatus
    {
        return ItemStatus::from($this->validated('status'));
    }
}
