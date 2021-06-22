<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Http\Requests\Common\ArticleCreateRequest;
use App\Http\Requests\Common\ArticleUpdateRequest;
use App\Http\Resources\Common\ArticleResource;
use App\Models\Common\Article;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admins')->except('index', 'show');
        //        $this->authorizeResource(Article::class, 'article');
    }

    /**
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return ArticleResource::collection(Article::all());
    }

    /**
     * @param  ArticleCreateRequest  $request
     * @param  Article               $article
     *
     * @return ArticleResource
     */
    public function store(ArticleCreateRequest $request, Article $article): ArticleResource
    {
        $article->fill($request->validated());
        $article->save();
        return new ArticleResource($article);
    }

    /**
     * @param  Article  $article
     *
     * @return ArticleResource
     */
    public function show(Article $article): ArticleResource
    {
        return new ArticleResource($article);
    }

    /**
     * @param  ArticleUpdateRequest  $request
     * @param  Article               $article
     *
     * @return ArticleResource
     */
    public function update(ArticleUpdateRequest $request, Article $article): ArticleResource
    {
        $article->fill($request->validated());
        $article->save();
        return new ArticleResource($article);
    }

    /**
     * @param  Article  $article
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Article $article)
    {
        $this->authorize('delete', $article);
        if ($article->delete()) {
            return response()->json('The model was deleted', 200);
        };
        return response()->json('The model was not deleted', 400);
    }
}
