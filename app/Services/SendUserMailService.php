<?php

namespace App\Services;

use App\Jobs\SendUserMailJob;

class SendUserMailService
{
    public function run($details, $userEmail)
    {
        dispatch(new SendUserMailJob($details, $userEmail))
            ->delay(
                now()
                    ->addSeconds(2)
            );

        return true;
    }
}
