<?php

namespace Domain\Bargain\Actions;

use App\Models\User;
use App\Models\Store;
use Domain\Bargain\Entities\ProductGroup;
use Domain\Bargain\Enums\ProductGroupType;

class CreateProductGroup
{
    public function execute(Store $store, string $groupName, ProductGroupType $type)
    {
        return $store->productGroups()->save(new ProductGroup(['name' => $groupName, 'type' => $type->value]));
    }
}
