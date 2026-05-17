<?php

namespace Modules\Tasks\Contracts\Emails;

use Modules\Email\Models\Email;
use Modules\Email\Contracts\Abstract\EmailContract;;

class ApplicantShouldRateJob extends EmailContract
{
    public function handle(Email $email): bool
    {
        return true;
    }
}
