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
        $command_ref_request = $command->commandRefRequests()->create(['wallet_id' => Auth::user()->id, 'order' => $next_wallet->pivot?->order, 'reference_id' => $next_wallet->id]);
        if ($command_ref_request instanceof CommandRefRequest) {
            return $next_wallet->referral_link;
        }
        return false;
    }

    /**
     * @param array $wallet_arr_ids
     * @param int $id
     * @return array
     */
    public function handleCommandArray(array $wallet_arr_ids, int $id): array
    {
        $handled_arr = [];
        foreach ($wallet_arr_ids as $key => $id) {
            $handled_arr[$id] = ['order' => $key + 1];
        }
        return $handled_arr;
    }
}
