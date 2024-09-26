<?php
namespace ScriptFUSIONTest\Fixture;

use ScriptFUSION\Mapper\Mapping;
use ScriptFUSION\Mapper\Strategy\Callback;
use ScriptFUSION\Mapper\Strategy\Copy;
use ScriptFUSION\Mapper\Strategy\Regex;

class BarBucketAddressToAddresesMapping extends Mapping
{
    protected function createMapping()
    {
        return [
            'line1' => new Copy('Addresses->0->1'),
            'city' => new Callback(fn (array $data) => $this->extractCity($data['Addresses'][0][2])),
            'postcode' => new Regex(new Copy('Addresses->0->2'), '[.*\b(\d{5})]', 1),
            'country' => 'US',
        ];
    }

    private function extractCity($line)
    {
        return explode(',', $line, 2)[0];
    }
}
