<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustHosts as Middleware;

class TrustHosts extends Middleware
{
    /**
     * Get the host patterns that should be trusted.
     *
     * @return array<int, string|null>
     */
    public function hosts(): array
    {
        return [
            'anagataexecutive.co.id',
            'www.anagataexecutive.co.id',
            'anagataexecutive.com',
            'www.anagataexecutive.com',
            $this->allSubdomainsOfApplicationUrl(),
            'healthcheck.railway.app', // Allow Railway health checks
        ];
    }
}
