<?php

namespace App\Http\Controllers;

use App\Domain\Feed\Actions\RecordFeedItemClickAction;
use App\Models\Feed\FeedItem;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OutboundController extends Controller
{
    public function go(int $id, Request $request, RecordFeedItemClickAction $recordClickAction): Response
    {
        $feedItem = FeedItem::query()
            ->with('feedSource')
            ->findOrFail($id);

        $recordClickAction->execute($feedItem, $request);

        if (config('feed.show_intermediate_page', false)) {
            return response()->view('outbound.redirect', [
                'feedItem' => $feedItem,
            ]);
        }

        return redirect()->away($feedItem->url);
    }
}
