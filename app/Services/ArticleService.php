<?php


namespace App\Services;


use App\Http\Requests\Cabinet\QuestionUpdateRequest;
use App\Http\Requests\Common\ArticleCreateRequest;
use App\Http\Requests\Common\ArticleUpdateRequest;
use App\Models\Cabinet\Content;
use App\Models\Cabinet\Question;
use App\Models\Common\Article;
use App\Models\Language;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ArticleService
{
    public function storeResource(ArticleCreateRequest $request, Article $article): Article|\Throwable
    {
        DB::beginTransaction();
        try {
            $article->user_id = $request->user()->id;
            $article->save();
            foreach ($request->input('content') as $content_params) {
                $content = new Content([
                    'text'        => Arr::get($content_params, 'text'),
                    'language_id' => Language::where('shortcode', Arr::get($content_params, 'language_shortcode'))->firstOrFail()->id,
                    'subject' => Arr::get($content_params, 'title'),
                ]);
                $article->contents()->save($content);
            }

            $article->refresh();
            DB::commit();
            return $article;
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            throw $e;
        }

    }

    /**
     * @param  ArticleUpdateRequest  $request
     * @param  Article               $article
     *
     * @return Article|\Throwable
     * @throws \Throwable
     */
    public function updateResource(ArticleUpdateRequest $request, Article $article): Article|\Throwable
    {
        DB::beginTransaction();
        try {

            if ($request->has('content')) {
                foreach ($request->input('content') as $content_params) {
                    $content       = $article->contents()->where('language_id',
                        Language::where('shortcode',
                            Arr::get($content_params, 'language_shortcode'))
                            ->firstOrFail()->id)
                        ->firstOrNew();
                    $content->text = Arr::get($content_params, 'text');
                    $content->subject = Arr::get($content_params, 'title');
                    $article->contents()->save($content);
                }
            }
            $article->refresh();
            DB::commit();
            return $article;
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            throw $e;
        }

    }
}
