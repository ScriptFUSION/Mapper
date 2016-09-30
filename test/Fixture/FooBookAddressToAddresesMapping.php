<?php
namespace ScriptFUSIONTest\Fixture;

use ScriptFUSION\Mapper\Mapping;
use ScriptFUSION\Mapper\Strategy\Copy;

class FooBookAddressToAddresesMapping extends Mapping
{
    protected function createMapping()
    {
        return [
            'line1' => new Copy('address->address_line1'),
            'line2' => new Copy('address->address_line2'),
            'city' => new Copy('address->city'),
            'postcode' => new Copy('address->post_code'),
            'country' => new Copy('country'),
        ];
    }
}
