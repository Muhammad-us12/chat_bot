<?php

namespace App\Models;

use Domain\Bargain\Entities\ProductGroup;
use App\Clients\Shopify\Client;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

class Store extends Model implements AuthenticatableContract
{
    use HasFactory, SoftDeletes, Authenticatable;

    private ?Client $client = null;

    protected $fillable = ['name', 'access_token', 'scopes'];

    public function shopifyClient(): Client
    {
        if (\is_null($this->client)) {
            $this->client = new Client($this);
        }

        return $this->client;
    }

    public function hasValidAccessToken(): bool
    {
        return $this->shopifyClient()->isAccessTokenWorking();
    }

    public function productGroups()
    {
        return $this->hasMany(ProductGroup::class,'store_id');
    }
}
