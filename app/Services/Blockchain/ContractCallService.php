<?php declare(strict_types=1);

namespace App\Services\Blockchain;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class ContractCallService
{
    use TronDecoder;

    /**
     * @return mixed
     * @throws \App\Exceptions\Ethereum\ContractException
     */
    public function getFirstUser(): ?string
    {
        $data = [
            "contract_address" => $this->base58check2HexString(config('contract.address')),
            "function_selector" => "idToAddress(uint256)",
            "owner_address" => "410000000000000000000000000000000000000000",
            "parameter" => "0000000000000000000000000000000000000000000000000000000000000001"
        ];

        $request = Http::post(
            config('tron.tron_host_api').'/wallet/triggersmartcontract',
            $data
        );

        /// decode bytes response
        $res = Arr::first($request->object()->constant_result);
        $args = [];
        $length = strlen($res);
        for ($i = 0; $i < $length; $i += 64) {
            $str = substr($res, $i, 64);
            $args [] = $str;
        }
        if (count($args) < 3) {
            return null;
        }
        return $this->hexString2Base58('0x'.substr($args[2], 0, 40));
    }
}
