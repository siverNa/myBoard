<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use App\Models\Post;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $channels = Channel::query()
            ->where('status', 'active')
            ->withCount('posts')
            ->orderByDesc('posts_count')
            ->orderBy('name')
            ->limit(6)
            ->get();
        
        $channelPks = $channels->pluck('pk')->all();
        
        $latestPostsByChannel = array();
        
        if (!empty($channelPks)) {
            $posts = Post::query()
                ->whereIn('channel_pk', $channelPks)
                ->where('is_hidden', false)
                ->orderBy('channel_pk')
                ->orderByDesc('pk')
                ->get();
            
            foreach ($posts as $post) {
                if (!isset($latestPostsByChannel[$post->channel_pk])) {
                    $latestPostsByChannel[$post->channel_pk] = array();
                }
                
                if (count($latestPostsByChannel[$post->channel_pk]) < 5) {
                    $latestPostsByChannel[$post->channel_pk][] = $post;
                }
            }
        }
        
        return view('home', compact('channels', 'latestPostsByChannel'));
    }
}