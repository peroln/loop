<?php


namespace App\Services;


use App\Models\Command;
use App\Models\CommandRefRequest;
use App\Models\Wallet;
use Illuminate\Support\Facades\Auth;

class CommandHandlerService
{
    public CommandRefRequest $commandRefRequest;

    /**
     * CommandHandlerService constructor.
     * @param CommandRefRequest $commandRefRequest
     */
    public function __construct(CommandRefRequest $commandRefRequest)
    {
        $this->commandRefRequest = $commandRefRequest;
    }

    /**
     * @param Command $command
     * @return Wallet
     */
    public function getNextReferenceInCommandRef(Command $command): Wallet
    {
        $last_order = 1;
        $last_record_ref_request = $this->commandRefRequest->where('command_id', $command->id)->latest()->first();
        if ($last_record_ref_request) {
            $last_order = $last_record_ref_request->order;
            $this->commandRefRequest->where('command_id', $command->id)->delete();
        }
        $next_wallets = $command->wallets()->wherePivot('order', '>', $last_order)->orderBy('order')->first();
        return $next_wallets ?? $command->wallets()->wherePivot('order', 1)->orderBy('order')->first();
    }

    /**
     * @param string $ref
     * @return string|bool
     */
    public function createNewRequest(string $ref): string|bool
    {
        $command = Command::where('reference', $ref)->firstOrFail();
        $next_wallet = $this->getNextReferenceInCommandRef($command);
        $command_ref_request = $command->commandRefRequests()->create(['order' => $next_wallet->pivot?->order, 'reference_id' => $next_wallet->id]);
        if ($command_ref_request instanceof CommandRefRequest) {
            return $next_wallet->referral_link;
        }
        return false;
    }

    /**
     * @param array $contract_user_ids
     * @param int $owner_id
     * @return array
     */
    public function handleCommandArray(array $contract_user_ids, int $contract_user_id): array
    {
        if ($contract_user_ids[0] !== $contract_user_id) {
            if (in_array($contract_user_id, $contract_user_ids, true)) {
                $key = array_search($contract_user_id, $contract_user_ids, true);
                unset($contract_user_ids[$key]);
            }
            array_unshift($contract_user_ids, $contract_user_id);
        }
        $arr_wallets = Wallet::whereIn('contract_user_id', $contract_user_ids)->pluck('contract_user_id', 'id')->toArray();

        $handled_arr = collect($contract_user_ids)->map(function ($item, $key) use ($arr_wallets) {
            return array_search($item, $arr_wallets, true);
        })->toArray();
        $arr = [];
        foreach ($handled_arr as $key => $id) {
            $arr[$id] = ['order' => $key + 1];
        }
        return $arr;
    }
}
