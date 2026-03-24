<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use Illuminate\Http\Request;

class ChannelController extends Controller
{
    public function index()
    {
        $channels = Channel::query()
            ->where('status', 'active')
            ->withCount('posts')
            ->orderByDesc('posts_count')
            ->orderBy('name')
            ->get();
        
        return view('channels.index', compact('channels'));
    }
    
    public function show($channelPk)
    {
        $channel = Channel::query()
            ->where('status', 'active')
            ->where('pk', $channelPk)
            ->firstOrFail();
        
        return view('channels.show', compact('channel'));
    }
}
