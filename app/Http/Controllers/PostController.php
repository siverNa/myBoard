<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function create($channelPk)
    {
        $channel = Channel::query()
            ->where('status', 'active')
            ->with('categories')
            ->where('pk', $channelPk)
            ->firstOrFail();
        
        return view('posts.create', compact('channel'));
    }
    
    public function store(Request $request, $channelPk)
    {
        $channel = Channel::query()
            ->where('status', 'active')
            ->with('categories')
            ->where('pk', $channelPk)
            ->firstOrFail();
        
        $request->validate([
            'category_pk' => 'required|integer',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);
        
        $categoryExists = $channel->categories
            ->where('pk', (int) $request->category_pk)
            ->isNotEmpty();
        
        if (!$categoryExists) {
            return back()
                ->withErrors(['category_pk' => '해당 채널에 속한 카테고리만 선택할 수 있습니다.'])
                ->withInput();
        }
        
        Post::query()->create([
            'channel_pk' => $channel->pk,
            'category_pk' => $request->category_pk,
            'user_pk' => Auth::id(),
            'title' => $request->title,
            'content' => $request->content,
            'view_count' => 0,
            'is_hidden' => false,
        ]);
        
        return redirect()
            ->route('channels.show', $channel->pk)
            ->with('success', '게시글이 등록되었습니다.');
    }
    
    public function show(Request $request, $postPk)
    {
        $post = Post::query()
            ->where('is_hidden', false)
            ->with([
                'channel.channelUserRoles',
                'category',
                'user',
                'comments.user',
            ])
            ->withCount('comments')
            ->where('pk', $postPk)
            ->firstOrFail();
        
        $post->increment('view_count');
        $post->refresh();
        
        $post->load([
            'channel.channelUserRoles',
            'category',
            'user',
            'comments.user',
        ]);
        $post->loadCount('comments');
        
        $editCommentPk = (int) $request->query('edit_comment_pk', 0);
        
        return view('posts.show', compact('post', 'editCommentPk'));
    }
    
    public function edit($postPk)
    {
        $post = Post::query()
            ->with(['channel.categories', 'channel.channelUserRoles'])
            ->where('pk', $postPk)
            ->firstOrFail();
        
        if (!$this->canEditPost($post)) {
            abort(403);
        }
        
        return view('posts.edit', compact('post'));
    }
    
    public function update(Request $request, $postPk)
    {
        $post = Post::query()
            ->with(['channel.categories', 'channel.channelUserRoles'])
            ->where('pk', $postPk)
            ->firstOrFail();
        
        if (!$this->canEditPost($post)) {
            abort(403);
        }
        
        $request->validate([
            'category_pk' => 'required|integer',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);
        
        $categoryExists = $post->channel->categories
            ->where('pk', (int) $request->category_pk)
            ->isNotEmpty();
        
        if (!$categoryExists) {
            return back()
                ->withErrors(['category_pk' => '해당 채널에 속한 카테고리만 선택할 수 있습니다.'])
                ->withInput();
        }
        
        $post->update([
            'category_pk' => $request->category_pk,
            'title' => $request->title,
            'content' => $request->content,
        ]);
        
        return redirect()
            ->route('posts.show', $post->pk)
            ->with('success', '게시글이 수정되었습니다.');
    }
    
    public function destroy($postPk)
    {
        $post = Post::query()
            ->with(['channel.channelUserRoles'])
            ->where('pk', $postPk)
            ->firstOrFail();
        
        if (!$this->canDeletePost($post)) {
            abort(403);
        }
        
        $user = Auth::user();
        // 사용자 삭제 시, 아예 제거
        if ($user && (int) $user->pk === (int) $post->user_pk) {
            $channelPk = $post->channel_pk;
            $post->delete();
            
            return redirect()
                ->route('channels.show', $channelPk)
                ->with('success', '게시글이 삭제되었습니다.');
        }
        // 관리자 삭제 시, 숨김 처리
        $post->update([
            'is_hidden' => true,
        ]);
        
        return redirect()
            ->route('channels.show', $post->channel_pk)
            ->with('success', '게시글이 숨김 처리되었습니다.');
    }
    
    private function canEditPost(Post $post): bool
    {
        $user = Auth::user();
        
        if (!$user) {
            return false;
        }
        
        if ((int) $user->pk === (int) $post->user_pk) {
            return true;
        }
        
        return $user->canManageChannel($post->channel);
    }
    
    private function canDeletePost(Post $post): bool
    {
        return $this->canEditPost($post);
    }
}