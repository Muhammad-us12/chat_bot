<?php

namespace App\Clients\Shopify;

use App\Models\Store;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\PendingRequest;
use App\Clients\Objects\GraphQl\GetAProduct;
use App\Clients\Objects\GraphQl\GetProducts;
use App\Clients\Objects\GraphQl\CreateACustomer;
use App\Clients\Objects\GraphQl\GetProductVariant;
use App\Clients\Objects\GraphQl\CreatePriceRuleAndGetDiscountCode;

class Client
{
    private string $version = "2023-10";
    private PendingRequest $http;


    public function __construct(private Store $store)
    {
        $baseUrl = "https://{$store->name}/admin/api/{$this->version}";

        $this->http = Http::withHeaders([
            'X-Shopify-Access-Token' => $store->access_token
        ])->acceptJson()->retry(3, 30)->throw()->baseUrl($baseUrl);
    }

    public function getClientVersion()
    {
        return $this->version;
    }

    public function getVariant(string $variantId): array
    {
        return $this->http->get("/variants/{$variantId}.json")->json('variant');
    }

    public function getProduct(string $productId): array
    {
        return $this->http->get("/products/{$productId}.json")->json('product');
    }

    public function getAllProduct(): array
    {
        return $this->http->get("products.json")->json('products');
        return $this->http->get("pages.json")->json('products');
        
    }

    public function createCustomer($customer): array
    {
        return $this->http->post("unstable/customers.json", ['customer' => $customer])->json('customer');
    }

    public function createPriceRule($priceRule)
    {
        return $this->http->post("price_rules.json", ['price_rule' => $priceRule])->json('price_rule');
    }

    public function createDiscountCode($priceRuleId, $discountCodePayloads)
    {
        return $this->http->post("price_rules/{$priceRuleId}/discount_codes.json", ['discount_code' => $discountCodePayloads])->json('discount_code');
    }

    public static function fetchAccessToken(string $shopAddress, string $code): array
    {
        $payload = [
            'client_id' => \config('services.shopify.key'),
            'client_secret' => \config('services.shopify.secret'),
            'code' => $code
        ];

        return Http::retry(3)->post('https://' . $shopAddress . '/admin/oauth/access_token?' . \http_build_query($payload))->json();
    }

    public function isAccessTokenWorking(): bool
    {
        $query = <<<QUERY
            {
            shop {
                name
            }
        }
        QUERY;

        $res = $this->http->post('/graphql.json', \compact('query'));

        return $res->ok() && $res->json('data.shop.name', false);
    }

    public function webhookSubscriptions(int $count = 10)
    {
        $query = <<<Query
        {
            webhookSubscriptions (first:{$count}) {
                edges {
                    node {
                        id
                        topic
                        endpoint {
                            __typename
                            ... on WebhookHttpEndpoint {
                              callbackUrl
                            }
                        }
                    }
                }
            }
          }
        Query;

        $webhookSubscriptions = $this->http->post('/graphql.json', \compact('query'))->json('data.webhookSubscriptions.edges');

        return \array_map(function ($webhook) {
            return [
                'id' => $webhook['node']['id'],
                'topic' => $webhook['node']['topic'],
                'callbackUrl' => $webhook['node']['endpoint']['callbackUrl'],
            ];
        }, $webhookSubscriptions);
    }

    public function createWebhookSubscription(string $topic, string $callbackUrl): string
    {
        $query = '
        mutation webhookSubscriptionCreate($topic: WebhookSubscriptionTopic!, $webhookSubscription: WebhookSubscriptionInput!) {
            webhookSubscriptionCreate(topic: $topic, webhookSubscription: $webhookSubscription) {
                userErrors {
                    field
                    message
                }
                webhookSubscription {
                    id
                    topic
                    format
                    endpoint {
                        __typename
                        ... on WebhookHttpEndpoint {
                            callbackUrl
                        }
                    }
                }
            }
        }
        ';
        $variables = [
            "topic" => $topic,
            "webhookSubscription" => [
                "callbackUrl" => $callbackUrl,
                "format" => "JSON"
            ]
        ];

        return $this->http->post('/graphql.json', \compact('query', 'variables'))->json('data.webhookSubscriptionCreate.webhookSubscription.id');
    }

    public function deleteSubscription(string $id): bool
    {
        $query = '
        mutation webhookSubscriptionDelete($id: ID!) {
            webhookSubscriptionDelete(id: $id) {
              userErrors {
                field
                message
              }
              deletedWebhookSubscriptionId
            }
          }
        ';
        $variables = \compact('id');

        return $this->http->post('/graphql.json', \compact('query', 'variables'))->json('data.webhookSubscriptionDelete.deletedWebhookSubscriptionId') == $id;
    }

    public function shopInfo(): array
    {
        return $this->http->get('/shop.json')->json();
    }

    public function graphQlRequestExecute($query)
    {
        return $this->http->post("graphql.json", ['query' => "$query"])->json();
    }

 

   
}
