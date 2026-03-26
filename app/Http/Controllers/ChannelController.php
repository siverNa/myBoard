<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use App\Models\Post;
use Illuminate\Http\Request;

class ChannelController extends Controller
{
    public function index(Request $request)
    {
        $keyword = trim($request->query('keyword', ''));
        
        $channelsQuery = Channel::query()
            ->where('status', 'active')
            ->withCount('posts');
        
        if ($keyword !== '') {
            $channelsQuery->where('name', 'like', '%' . $keyword . '%');
        }
        
        $channels = $channelsQuery
            ->orderByDesc('posts_count')
            ->orderBy('name')
            ->get();
        
        return view('channels.index', compact('channels', 'keyword'));
    }
    
    public function show(Request $request, $channelPk)
    {
        $channel = Channel::query()
            ->where('status', 'active')
            ->with(['categories'])
            ->where('pk', $channelPk)
            ->firstOrFail();
        
        $selectedCategoryPk = $request->query('category_pk');
        
        $postsQuery = Post::query()
            ->where('channel_pk', $channel->pk)
            ->where('is_hidden', false)
            ->with(['category', 'user'])
            ->withCount('comments')
            ->orderByDesc('pk');
        
        if (!empty($selectedCategoryPk)) {
            $postsQuery->where('category_pk', $selectedCategoryPk);
        }
        
        $posts = $postsQuery->paginate(10)->withQueryString();
        
        return view('channels.show', compact(
            'channel',
            'posts',
            'selectedCategoryPk'
        ));
    }
}
