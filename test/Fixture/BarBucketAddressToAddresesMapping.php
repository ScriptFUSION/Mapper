<?php
namespace ScriptFUSIONTest\Fixture;

use ScriptFUSION\Mapper\Mapping;
use ScriptFUSION\Mapper\Strategy\Callback;
use ScriptFUSION\Mapper\Strategy\Copy;

class BarBucketAddressToAddresesMapping extends Mapping
{
    protected function createMapping()
    {
        return [
            'line1' => new Copy('Addresses->0->1'),
            'city' => new Callback(
                function (array $data) {
                    return $this->extractCity($data['Addresses'][0][2]);
                }
            ),
            'postcode' => new Callback(
                function (array $data) {
                    return $this->extractZipCode($data['Addresses'][0][2]);
                }
            ),
            'country' => 'US',
        ];
    }

    private function extractCity($line)
    {
        return explode(',', $line, 2)[0];
    }

    private function extractZipCode($line)
    {
        if (preg_match('[.*\b(\d{5})]', $line, $matches)) {
            return $matches[1];
        }
    }
}
