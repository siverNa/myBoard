<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, $postPk)
    {
        $post = Post::query()
            ->where('is_hidden', false)
            ->where('pk', $postPk)
            ->firstOrFail();
        
        $request->validate([
            'content' => 'required|string',
        ]);
        
        Comment::query()->create([
            'post_pk' => $post->pk,
            'user_pk' => Auth::id(),
            'content' => $request->content,
        ]);
        
        return redirect()
            ->route('posts.show', $post->pk)
            ->with('success', '댓글이 등록되었습니다.');
    }
    
    public function update(Request $request, $commentPk)
    {
        $comment = Comment::query()
            ->with(['post.channel.channelUserRoles', 'user'])
            ->where('pk', $commentPk)
            ->firstOrFail();
            
        if (!$this->canManageComment($comment)) {
            abort(403);
        }
        
        $request->validate([
            'content' => 'required|string',
        ]);
        
        $comment->update([
            'content' => $request->content,
        ]);
        
        return redirect()
            ->route('posts.show', $comment->post_pk)
            ->with('success', '댓글이 수정되었습니다.');
    }
    
    public function destroy($commentPk)
    {
        $comment = Comment::query()
            ->with(['post.channel.channelUserRoles', 'user'])
            ->where('pk', $commentPk)
            ->firstOrFail();
        
        if (!$this->canManageComment($comment)) {
            abort(403);
        }
        
        $postPk = $comment->post_pk;
        
        $comment->delete();
        
        return redirect()
            ->route('posts.show', $postPk)
            ->with('success', '댓글이 삭제되었습니다.');
    }
    
    private function canManageComment(Comment $comment): bool
    {
        $user = Auth::user();
        
        if (!$user) {
            return false;
        }
        
        if ((int) $user->pk === (int) $comment->user_pk) {
            return true;
        }
        
        return $user->canManageChannel($comment->post->channel);
    }
}