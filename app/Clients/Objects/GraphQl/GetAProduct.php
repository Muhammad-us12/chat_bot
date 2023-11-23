<?php

namespace App\Clients\Objects\GraphQl;

class GetAProduct
{
    public $query;

    public function __construct(string $productId)
    {
        $this->query = '
         query FetchingProduct($variantCount:Int = 100,$id:ID="' . $productId . '"){
        product(id:$id){
         id
         title
         handle
         vendor
         tags
         variants(first:$variantCount){
           edges{
             node{
               id
               displayName
               price
        }
      }
    }
  }
}';
    }

    public function __toString()
    {
        return $this->query;
    }
}
