<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\NavigationMenu;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
        $articles = Article::latest()->get();
        $menus = NavigationMenu::where('type', 'landing_page')->orderBy('order')->get();
        return view('blog.index', compact('articles', 'menus'));
    }

    public function show($slug)
    {
        $article = Article::where('slug', $slug)->firstOrFail();
        $related = Article::where('id', '!=', $article->id)->latest()->take(3)->get();
        $menus = NavigationMenu::where('type', 'landing_page')->orderBy('order')->get();
        
        // Parse basic Gutenberg editor markdown tags (bold, headings, images)
        $content = htmlspecialchars($article->content, ENT_QUOTES, 'UTF-8');
        
        // Image tag: ![AltText](url)
        $content = preg_replace('/\!\[(.*?)\]\((.*?)\)/', '<img src="$2" alt="$1" class="max-w-full my-8 rounded-2xl border border-slate-200/60 shadow-md mx-auto block">', $content);
        
        // H2 Heading tag: ## Text
        $content = preg_replace('/^\#\# (.*?)$/m', '<h2 class="text-xl sm:text-2xl font-bold text-slate-900 mt-8 mb-4">$1</h2>', $content);
        
        // Bold: **Text**
        $content = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $content);
        
        // Italic: *Text*
        $content = preg_replace('/\*(.*?)\*/', '<em>$1</em>', $content);
        
        $parsedContent = nl2br($content);
        
        return view('blog.show', compact('article', 'related', 'menus', 'parsedContent'));
    }
}
