<?php

namespace Modules\Tasks\Http\Requests;

use Orion\Http\Requests\Request;
use Modules\Reviews\Models\Interaction;

class FavoriteRequest extends Request
{

    public function storeRules(): array
    {

        $whitelist = Interaction::getWhitelistedModels();
        $whitelistKeys = array_keys($whitelist);

        
        return [
            'target' => 'required|string|in:' . implode(',', $whitelistKeys),
            'target_id' => 'required|integer|exists:' . $this->input('target') . ',id',
            'favorite' => 'sometimes|boolean',
        ];

    }


    public function updateRules(): array
    {
        // Vi bruger samme regler for update som for store
        return $this->storeRules();

    }


    public function messages(): array
    {

        $whitelist = Interaction::getWhitelistedModels();

        $whitelistKeys = implode("', '", array_keys($whitelist));


        return [
            'target.required' => 'Mål-typen er påkrævet.',
            'target.string' => 'Mål-typen skal være en tekststreng.',
            'target.in' => "Mål-typen skal være en af følgende: '$whitelistKeys'.",

            'target_id.required' => 'Mål-ID er påkrævet.',
            'target_id.integer' => 'Mål-ID skal være et helt tal.',
            'target_id.exists' => 'Mål-ID findes ikke i den angivne model.',

            'favorite.boolean' => 'Favoritværdien skal være sand eller falsk.',
        ];

    }

}
