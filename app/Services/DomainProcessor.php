<?php

namespace App\Services;

use App\Models\Contact;
use App\Models\Domain;
use Illuminate\Support\Arr;
use Messerli90\Hunterio\Hunter;

class DomainProcessor
{

    public function process(Domain $domain)
    {
        $apiKey = config('services.hunter.key');

        $client = new Hunter($apiKey);
        
        $result = $client->domainSearch($domain->domain_name);

        $emails = Arr::get($result, 'data.emails', []);
        
        foreach($emails as $entry){
            Contact::create([
                'domain_id'  => $domain->id,
                'email'      => $entry['value'],
                'first_name' => $entry['first_name'],
                'last_name'  => $entry['last_name'],
                'confidence' => $entry['confidence'],
            ]);
        }

    }
}