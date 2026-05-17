<?php

namespace Modules\Tasks\Http\Requests;

use Orion\Http\Requests\Request;
use Modules\Companies\Rules\UserIsOwnerOfCompany;
use Modules\Tasks\Models\Tasks;
use App\Rules\TagsRule;

class TaskRequest extends Request
{
    public function storeRules(): array
    {
        $user_id = $this->user()->id;
        $company_id = $this->company_id;
        $save_as_draft = $this->boolean("save_as_draft");


        if ($save_as_draft) {

            return [
                'type' => 'required|string|in:job,application',
            ];

        }


        return [
            'name' => 'required',
            'description' => 'sometimes',
            'user_id' => [
                'required',
                'exists:users,id',
                new UserIsOwnerOfCompany($company_id, $user_id),
            ],
            'company_id' => 'sometimes|nullable|exists:companies,id',
            'type' => 'required|string|in:job,application',
            'categories' => 'required|array',
            'price' => 'required',
            'status' => 'required',
            'amount' => 'required',

            'address' => 'required|array',
            'address.street' => 'required',
            'address.post_code' => 'required',
            'address.city' => 'required',

            'contact' => 'sometimes|array',
            'contact.email' => 'sometimes|email',
            'contact.phone' => 'sometimes',

            'available.always_available' => 'sometimes|integer',
            'available.from' => 'required_without:available.always_available|date',
            'available.to' => 'required_without:available.always_available|date|after_or_equal:today',

            'tags' => ['sometimes', new TagsRule],

            'sync_media' => 'sometimes|boolean',

            'uploads' => 'sometimes|array',
            'uploads.*' => 'required_with:uploads|file',

            'attatchments' => 'sometimes|array',
            'attatchments.*' => 'required_with:attatchments|integer|exists:media,id',
        ];
    }

    public function updateRules(): array
    {
        $user_id = $this->user()->id;
        $id = $this->route("task");
        $task = Tasks::find($id);
        $company_id = $task ? $task->company_id : null;

        return [
            'name' => 'sometimes|nullable',
            'description' => 'sometimes',
            'user_id' => [
                'sometimes',
                'nullable',
                'exists:users,id',
                new UserIsOwnerOfCompany($company_id, $user_id),
            ],
            'company_id' => 'sometimes|nullable|exists:companies,id',
            'type' => 'sometimes|string|in:job,application',
            'categories' => 'sometimes|array',
            'price' => 'sometimes',
            'amount' => 'sometimes',
            'status' => 'sometimes',

            'address' => 'sometimes|array',
            'address.street' => 'sometimes',
            'address.post_code' => 'sometimes',
            'address.city' => 'sometimes',

            'contact' => 'sometimes|array',
            'contact.email' => 'sometimes|email',
            'contact.phone' => 'sometimes',

            'available.always_available' => 'sometimes|integer',
            'available.from' => 'sometimes|date',
            'available.to' => 'sometimes|date|after_or_equal:today',

            'tags' => ['sometimes', new TagsRule],

            'sync_media' => 'sometimes|boolean',

            'uploads' => 'sometimes|array',
            'uploads.*' => 'required_with:uploads|file',

            'attatchments' => 'sometimes|array',
            'attatchments.*' => 'required_with:attatchments|integer|exists:media,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Projektnavn er påkrævet.',
            'user_id.required' => 'Bruger-id er påkrævet.',
            'company_id.exists' => 'Virksomheden findes ikke.',
            'type.required' => 'Projekttype er påkrævet.',
            'type.in' => 'Projekttype skal være enten "job" eller "application".',
            'categories.required' => 'Kategorier er påkrævet.',
            'address.required' => 'Adresse er påkrævet.',
            'address.street.required' => 'Vejnavn er påkrævet.',
            'address.post_code.required' => 'Postnummer er påkrævet.',
            'address.city.required' => 'By er påkrævet.',
            'available.from.required_without' => 'Startdato er påkrævet.',
            'available.to.required_without' => 'Slutdato er påkrævet.',
            'available.from.date' => 'Startdato skal være en gyldig dato.',
            'available.to.date' => 'Slutdato skal være en gyldig dato.',
            'available.to.after_or_equal' => 'Slutdato skal være i dag eller i fremtiden.',
            'contact.email.email' => 'Email-adressen skal være gyldig.',
            'uploads.file' => 'Uploads skal være en gyldig fil.',
            'attatchments.array' => 'Attachments skal være et array.',
            'attatchments.*.exists' => 'Hvert attachment skal findes i mediebiblioteket.',
        ];
    }
}
