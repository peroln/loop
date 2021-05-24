<?php


namespace App\Services\EventsHandlers;


use App\Repositories\TransactionRepository;
use App\Services\Blockchain\TronDecoder;
use App\Services\TronService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

abstract class  BaseEventsHandler
{
    use  TronDecoder;

    private TransactionRepository $transactionRepository;

    public function __construct(TransactionRepository $transactionRepository)
    {
        $this->transactionRepository = $transactionRepository;
    }

    /**
     * @param Collection $collect_event_array
     * @param array $arr_names
     * @return array
     */
    public function extractEvents(Collection $collect_event_array, array $arr_names): array
    {
        $except_transaction_ids = $this->transactionRepository->retrieveHexIdRegistration($arr_names);
        return $collect_event_array
            ->whereNotIn("transaction_id", $except_transaction_ids)
            ->whereIn('event_name', $arr_names)
            ->all();
    }

    abstract public function extractDataFromTransaction(array $event): bool|array;

    public function extractEventData(Collection $response)
    {
        $events = $this->extractEvents($response, [static::EVENT_NAME]);
        $events = collect($events);
        $params = [];
        if ($events->count()) {
            try {
                foreach ($events as $event) {
                    if (is_array($event) && count($event)) {
                        $array_dada_events = $this->extractDataFromTransaction($event);
                        if (is_array($array_dada_events) && count($array_dada_events)) {
                            $params[] = array_merge($array_dada_events, ['model_service' => TronService::class]);
                        };
                    }
                }
            } catch (\Throwable $e) {
                Log::debug($e->getMessage());
            }

        }
        return $params;
    }

    public function handleResponse(Collection $response)
    {
        $params = $this->extractEventData($response);
        if (count($params)) {
            foreach ($params as $param) {
                $this->createNewResource($param);
            }
        }

    }

    abstract public function createNewResource(array $params): void;
}
