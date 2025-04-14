<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::latest()->get();
        return view('articles.index', compact('articles'));
    }
    public function create()
    {
        return view('articles.create');
    }
    public function edit(Article $article)
    {
        return view('articles.edit', compact('article'));
    }
    public function update(Request $request, Article $article)
    {
        $request->validate(
            [
            'title' => 'required|max:255',
            'body' => 'required',
            ]
        );

        $article->update(
            [
            'title' => $request->input('title'),
            'body' => $request->input('body'),
            ]
        );

        return redirect('/articles');
    }
    public function destroy(Article $article)
    {
        $article->delete();

        return redirect('/articles');
    }
    public function show(Article $article)
    {
        return view('articles.show', compact('article'));
    }


}
