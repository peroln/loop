<?php


namespace App\Http\Controllers\Service;

use App\Http\Requests\Service\LeagueRatingRequest;
use App\Http\Requests\Service\PartnerRequest;
use App\Http\Resources\User\UserPartnerResource;
use App\Models\User;
use App\Services\CabinetService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class CabinetController
{
    private CabinetService $cabinetService;

    public function __construct(CabinetService $cabinetService)
    {
        $this->cabinetService = $cabinetService;
    }

    /**
     * @return JsonResponse
     */
    public function mainInformation(): JsonResponse
    {
        [$all_count, $users_invited_last_24_hour, $all_trx] = $this->cabinetService->mainInfoCabinet();
        return response()->json(compact('all_count', 'users_invited_last_24_hour', 'all_trx'));
    }

    /**
     * @return JsonResponse
     */
    public function leagueRating(LeagueRatingRequest $request): JsonResponse
    {
        return response()->json($this->cabinetService->RatingLeague($request->input('period', 'month')));
    }

    /**
     * @return JsonResponse
     */
    public function LeagueDesk(): JsonResponse
    {
        return response()->json($this->cabinetService->LeagueDesk());
    }

    /**
     * @param PartnerRequest $request
     * @return UserPartnerResource
     */
    public function partners(PartnerRequest $request): UserPartnerResource
    {
        return new UserPartnerResource(User::where('contract_user_id', $request->input('contract_user_id'))->firstOrFail());
    }
}
