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
            ->with(['categories', 'channelUserRoles.user',])
            ->where('pk', $channelPk)
            ->firstOrFail();
        
        $selectedCategoryPk = $request->query('category_pk');
        $keyword = trim($request->query('keyword', ''));
        
        $postsQuery = Post::query()
            ->where('channel_pk', $channel->pk)
            ->where('is_hidden', false)
            ->with(['category', 'user'])
            ->withCount('comments')
            ->orderByDesc('pk');
        // 카테고리 선택 시
        if (!empty($selectedCategoryPk)) {
            $postsQuery->where('category_pk', $selectedCategoryPk);
        }
        // 검색 쿼리
        if ($keyword !== '') {
            $postsQuery->where(function ($query) use ($keyword) {
                $query->where('title', 'like', '%' . $keyword . '%')
                    ->orWhere('content', 'like', '%' . $keyword . '%')
                    ->orWhereHas('user', function ($userQuery) use ($keyword) {
                        $userQuery->where('login_id', 'like', '%' . $keyword . '%');
                    });
            });
        }
        
        $posts = $postsQuery->paginate(10)->withQueryString();
        
        $ownerLoginId = null;
        $managerLoginIds = array();
        
        foreach ($channel->channelUserRoles as $channelUserRole) {
            if (!$channelUserRole->user) {
                continue;
            }
            
            if ($channelUserRole->role === \App\Models\ChannelUserRole::ROLE_OWNER) {
                $ownerLoginId = $channelUserRole->user->login_id;
            }
            
            if ($channelUserRole->role === \App\Models\ChannelUserRole::ROLE_MANAGER) {
                $managerLoginIds[] = $channelUserRole->user->login_id;
            }
        }
        
        return view('channels.show', compact(
            'channel',
            'posts',
            'selectedCategoryPk',
            'keyword',
            'ownerLoginId',
            'managerLoginIds'
        ));
    }
}
