<?php

namespace App\Http\Requests\Item;

use App\Enums\ItemStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndexItemRequest extends FormRequest
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
            'status' => [Rule::enum(ItemStatus::class)],
        ];
    }

    public function status(): ItemStatus
    {
        return $this->enum('status', ItemStatus::class, ItemStatus::Pending);
    }
}
