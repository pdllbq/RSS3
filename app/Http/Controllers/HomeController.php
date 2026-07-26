<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Feed\FeedItem;

class HomeController extends Controller
{
    public function index()
    {
        $feedItems = FeedItem::where('language', app()->getLocale())
            ->displayable()
            ->orderByDesc('published_at')
            ->with('feedSource')
            ->paginate(36);

        return view('home', compact('feedItems'));
    }
}
