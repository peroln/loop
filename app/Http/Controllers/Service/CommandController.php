<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use App\Http\Requests\Service\ChangeCommandRequest;
use App\Http\Requests\Service\RequestCommandRequest;
use App\Http\Resources\Service\CommandResource;
use App\Models\Command;
use App\Services\CommandHandlerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CommandController extends Controller
{

    public CommandHandlerService $commandHandlerService;

    public function __construct(CommandHandlerService $commandHandlerService)
    {

        $this->commandHandlerService = $commandHandlerService;
    }

    /**
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return CommandResource::collection(Command::with('wallets')->get());
    }


    /**
     * @return CommandResource|JsonResponse
     * @throws \Throwable
     */
    public function store(): CommandResource|JsonResponse
    {
        DB::beginTransaction();
        try {
            $wallet_id = Auth::user()->id;
            $command = Command::create(['reference' => Str::random(18), 'wallet_id' => $wallet_id]);
            $command->wallets()->attach($wallet_id, ['order' => 1]);
            $command->commandRefRequests()->create(['wallet_id' => $wallet_id, 'order' => 1, 'reference_id' => $wallet_id]);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 400);
        }

        return new CommandResource($command);
    }

    /**
     * @param Command $command
     * @return CommandResource
     */
    public function show(Command $command): CommandResource
    {
        return new CommandResource($command);
    }

    /**
     * @param Request $request
     * @param Command $command
     * @return CommandResource|JsonResponse
     */
    public function update(Request $request, Command $command): CommandResource|JsonResponse
    {
        $command->fill($request->all());
        if ($command->save()) {
            return new CommandResource($command);
        };
        return response()->json('The model was not updated', 400);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Command $command
     * @return JsonResponse
     */
    public function destroy(Command $command): JsonResponse
    {
        $command->wallets()->detach();
        $command->commandRefRequests()->delete();
        if ($command->delete()) {
            return response()->json('The model was deleted', 200);
        };
        return response()->json('The model was not deleted', 400);
    }

    /**
     * @param ChangeCommandRequest $request
     * @param Command $command
     * @return CommandResource
     */
    public function changeCommand(ChangeCommandRequest $request, Command $command): CommandResource
    {
        $handled_arr = $this->commandHandlerService->handleCommandArray($request->contract_user_ids, $command->wallet->contract_user_id);
        $command->wallets()->sync($handled_arr);
        $command->fresh();
        return new CommandResource($command);
    }

    /**
     * @param RequestCommandRequest $request
     * @return JsonResponse
     */
    public function requestCommand(RequestCommandRequest $request): JsonResponse
    {
        $command_ref = $request->input('ref');
        $referral = $this->commandHandlerService->createNewRequest($command_ref);
        return response()->json(compact('referral', 'command_ref'));
    }
}
