<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use App\Http\Requests\Service\ChangeCommandRequest;
use App\Http\Resources\Service\CommandResource;
use App\Models\Command;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CommandController extends Controller
{
    /**
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return CommandResource::collection(Command::with('wallets')->get());
    }


    /**
     * @return CommandResource
     */
    public function store(): CommandResource
    {
        $command = Command::create(['reference' => Str::random(18)]);
        $command->wallets()->attach(Auth::user()->id, ['order' => 1]);
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
        if($command->delete()){
            return response()->json('The model was deleted', 200);
        };
        return response()->json('The model was not deleted', 400);
    }

    /**
     * @param ChangeCommandRequest $request
     * @param int $id
     * @return CommandResource
     */
    public function changeCommand(ChangeCommandRequest $request, int $id): CommandResource
    {
        $handled_arr = $this->handleCommandArray($request->wallet_ids, $id);
        $command = Command::find($id);
        $command->wallets()->sync($handled_arr);
        $command->fresh();
        return new CommandResource($command);
    }

    /**
     * @param array $wallet_arr_ids
     * @param int $id
     * @return array
     */
    private function handleCommandArray(array $wallet_arr_ids, int $id): array
    {
        $handled_arr = [];
        foreach ($wallet_arr_ids as $key => $id) {
            $handled_arr[$id] = ['order' => $key + 1];
        }
        return $handled_arr;
    }
}
