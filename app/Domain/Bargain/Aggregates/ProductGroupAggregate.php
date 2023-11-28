<?php

namespace Domain\Bargain\Aggregates;

use Domain\Bargain\Entities\Product;
use App\Domain\Bargain\Entities\Bargain;
use Domain\Bargain\Entities\ProductGroup;
use Domain\Bargain\Enums\ProductGroupType;

class ProductGroupAggregate
{
    private $productGroup;
    public function __construct(private ProductGroup $productGroupObject)
    {
        $this->productGroup = $productGroupObject;
    }

    public function addProduct(Product $product): bool
    {
        if (ProductGroupType::from($this->productGroup->type) != ProductGroupType::CUSTOM) {
            return false;
        }
        $this->productGroup->products()->save($product);
        return true;
    }
    
    public function addBargainRule(Bargain $bargain)
    {
        if ($this->hasBargainRule()) {
            return false;
        }
        $bargain->product_group_id = $this->productGroup->id;

        return $bargain->save();
    }

    public function removeBargainRule()
    {
        $productGroupBargain = $this->productGroup->bargain;
        if ($productGroupBargain) {
            $productGroupBargain->delete();
        }
    }

    private function hasBargainRule(): bool
    {
        return $this->productGroup->bargain != null;
    }
}
