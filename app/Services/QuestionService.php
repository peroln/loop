<?php


namespace App\Services;


use App\Http\Requests\Cabinet\QuestionStoreRequest;
use App\Http\Requests\Cabinet\QuestionUpdateRequest;
use App\Models\Cabinet\Content;
use App\Models\Cabinet\Question;
use App\Models\Language;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class QuestionService
{
    /**
     * @param  QuestionStoreRequest  $request
     *
     * @return Question
     * @throws \Throwable
     */
    public function storeResource(QuestionStoreRequest $request): Question|\Throwable
    {
        DB::beginTransaction();
        try {
            $question = new Question();
            $question->user_id = $request->user()->user_id;
            $question->save();
            foreach ($request->input('content') as $content_params) {
                $content = new Content([
                    'text'        => Arr::get($content_params, 'text'),
                    'subject'        => Arr::get($content_params, 'subject'),
                    'language_id' => Language::where('shortcode', Arr::get($content_params, 'language_shortcode'))->firstOrFail()->id,
                ]);
                $question->contents()->save($content);
            }

            $question->refresh();
            DB::commit();
            return $question;
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            throw $e;
        }

    }

    /**
     * @param  QuestionUpdateRequest  $request
     * @param  Question               $question
     *
     * @return Question|string
     * @throws \Throwable
     */
    public function updateResource(QuestionUpdateRequest $request, Question $question): Question|\Throwable
    {
        DB::beginTransaction();
        try {

            if ($request->has('content')) {
                foreach ($request->input('content') as $content_params) {
                    $content       = $question->contents()->where('language_id',
                        Language::where('shortcode',
                            Arr::get($content_params, 'language_shortcode'))
                            ->firstOrFail()->id)
                        ->firstOrNew();
                    $content->text = Arr::get($content_params, 'text');
                    $content->subject = Arr::get($content_params, 'subject');
                    $question->contents()->save($content);
                }
            }
            $question->refresh();
            DB::commit();
            return $question;
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            throw $e;
        }

    }
}
