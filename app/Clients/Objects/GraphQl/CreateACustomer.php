<?php

namespace App\Clients\Objects\GraphQl;

class CreateACustomer
{
    public $query;

    public function __construct(String $email)
    {
        $this->query = '
  mutation {
  customerCreate(input: {email: "' . $email . '"}) {
    customer {
      id
      email
    }
    userErrors {
      field
      message
    }
  }
}

';
    }

    public function __toString()
    {
        return $this->query;
    }
}
