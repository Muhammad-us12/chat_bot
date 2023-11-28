<?php


namespace App\Clients\Objects\GraphQl;

class GetProductVariant
{
    public $query;

    public function __construct( string $variantId)
    {
        $this->query = '{
                            productVariant(id:"'.$variantId.'") {
                                id
                                title
                                price
                                product{
                                    id
                                }
                            }
                        }';
        return $this->query;
    }

    public function __toString()
    {
        return $this->query;
    }
}
