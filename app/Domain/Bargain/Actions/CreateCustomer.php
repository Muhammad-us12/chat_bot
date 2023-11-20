<?php

namespace Domain\Bargain\Actions;

use App\Models\User;
use App\Models\Customer;
use App\Clients\IpLocate\IpLocation;
use App\Models\Store;

class CreateCustomer
{
    public function __construct(private IpLocation $ipLocate)
    {
       
    }

    public function execute(Store $store, string $name, string $email, string $ip): Customer
    {
        $customer = $store->customers()->where('email', $email)->first();

        
        if ($customer) {
            return $customer;
        }
        $os = $this->getCustomerOs();
        
        $customerLocationInfo = $this->ipLocate->getLocation($ip);
        
        return $store->customers()->save(new Customer([
            'country' => $customerLocationInfo['country'] ?? null,
            'city' => $customerLocationInfo['city'] ?? null,
        ] + compact('name', 'email', 'os')));
    }

    private function getCustomerOs(): ?string
    {
        return rescue(fn () => \get_browser(null, true)['platform'] ?? null, null, false);
    }
}
