<?php

namespace App\Clients\Objects\GraphQl;

class GetProducts
{
    public $query;

    public function __construct($cursor,$location)
    {
        $args = [
            "first: 10",
            "sortKey:CREATED_AT",
            "reverse:true"
        ];
        if (!empty($cursor)) {
            if ($location === 'after') {
                $args[] = "after: \"$cursor\"";
            } else {
                $args = [] ;
                $args[] =  "before: \"$cursor\"";
                $args[] =   "sortKey:CREATED_AT";
                $args[] =    "reverse:true";
                $args[] =    "last:10";
            }
        }
        $args = implode(', ', $args);
        $this->query = <<<QUERY
        {
            products($args) {
                pageInfo {
                    hasNextPage,
                    hasPreviousPage
                },
                edges {
                    cursor,
                    node {
                        id,
                        title
                        createdAt
                    }
                }
            }
        }
        QUERY;
    }

    public function __toString()
    {
        return $this->query;
    }
}
