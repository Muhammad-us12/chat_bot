<?php

namespace Domain\Bargain\Enums;

enum ProductGroupType: string
{
    case TAG = 'tag';
    case COLLECTION = 'collection';
    case CUSTOM = 'custom';
}
