<?php


namespace App\Services;


use App\Http\Requests\Cabinet\AnswerStoreRequest;
use App\Http\Requests\Cabinet\AnswerUpdateRequest;
use App\Models\Cabinet\Answer;
use App\Models\Cabinet\Content;
use App\Models\Language;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AnswerService
{
    /**
     * @param  AnswerStoreRequest  $request
     *
     * @return Answer
     * @throws \Throwable
     */
    public function storeResource(AnswerStoreRequest $request): Answer|\Throwable
    {
        DB::beginTransaction();
        try {
            $answer = new Answer();
            $answer->fill($request->only(['question_id']));
            $user = $request->user();

            $answer->user_id = match (get_class($user)) {
                User::class => $user->id,
                Wallet::class => $user->user_id
            };
            $answer->save();
            foreach ($request->input('content') as $content_params) {
                $content = new Content([
                    'text'        => Arr::get($content_params, 'text'),
                    'language_id' => Language::where('shortcode', Arr::get($content_params, 'language_shortcode'))->firstOrFail()->id,
                ]);
                $answer->contents()->save($content);
            }

            $answer->refresh();
            DB::commit();
            return $answer;
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            throw $e;
        }

    }

    /**
     * @param  AnswerUpdateRequest  $request
     * @param  Answer               $answer
     *
     * @return Answer
     * @throws \Throwable
     */
    public function updateResource(AnswerUpdateRequest $request, Answer $answer): Answer|\Throwable
    {
        DB::beginTransaction();
        try {
            if ($request->has('content')) {
                foreach ($request->input('content') as $content_params) {
                    $content       = $answer->contents()->where('language_id',
                        Language::where('shortcode',
                            Arr::get($content_params, 'language_shortcode'))
                            ->firstOrFail()->id)
                        ->firstOrNew();
                    $content->text = Arr::get($content_params, 'text');
                    $answer->contents()->save($content);
                }
            }
            DB::commit();
            $answer->refresh();
            return $answer;
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            throw $e;
        }

    }
}
