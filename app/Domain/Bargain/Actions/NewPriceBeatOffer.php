<?php

namespace App\Domain\Bargain\Actions;

use App\Models\User;
use App\Models\PriceBeatOffer;
use Illuminate\Support\Facades\DB;
use Domain\Bargain\Actions\CreateCustomer;
use App\Domain\Bargain\Actions\ParseProductPage;
use App\Models\Store;

class NewPriceBeatOffer
{
    private $parseProductPage;
    private $createCustomer;

    public function __construct(private CreateCustomer $createCustomerObject, private ParseProductPage $parseProductPageObject)
    {
        $this->parseProductPage = $parseProductPageObject;
        $this->createCustomer = $createCustomerObject;
    }
    public function execute(Store $store, array $priceBeatRequestParams)
    {
        $offeredVariant = $store->shopifyClient()->getVariant($priceBeatRequestParams['variant_id']);
        $offeredProduct = $store->shopifyClient()->getProduct($offeredVariant['product_id']);
        
        $competitorProductInfo = $this->parseProductPage->execute($priceBeatRequestParams['competitor_url']);

        if (empty($competitorProductInfo)) {
            $offerAmount = null;
        } else {
            $storeCurrency = $store->shopifyClient()->shopInfo()['shop']['currency'];
            $offerAmount = (new \App\Clients\ExchangeRate\Client)->convert($storeCurrency, $competitorProductInfo['priceCurrency'], $competitorProductInfo['price']);
        }

        return DB::transaction(function () use ($store, $priceBeatRequestParams, $offeredProduct, $offeredVariant, $offerAmount) {
            $customer = $this->createCustomer->execute(
                $store,
                $priceBeatRequestParams['name'],
                $priceBeatRequestParams['email'],
                $priceBeatRequestParams['ip']
            );

            return $store->priceBeatOffers()
            ->save(new PriceBeatOffer([
                'customer_id' => $customer->id,
                'competitor_url' => $priceBeatRequestParams['competitor_url'],
                'variant_id' => $priceBeatRequestParams['variant_id'],
                'variant_name' => $offeredVariant['title'],
                'product_name' => $offeredProduct['title'],
                'product_id' => $offeredProduct['id'],
                'offered_amount' => $offerAmount,
                'actual_amount' => $offeredVariant['price'],
                'status' => 'pending'
            ]));
        });
    }
}
