<?php

namespace App\Http\Controllers;

use App\Models\PostAttachment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostAttachmentController extends Controller
{
    public function download($attachmentPk)
    {
        $attachment = PostAttachment::query()
            ->with(['post.channel.channelUserRoles'])
            ->where('pk', $attachmentPk)
            ->firstOrFail();

        if ($attachment->post->is_hidden) {
            abort(404);
        }

        if (!Storage::disk('public')->exists($attachment->file_path)) {
            abort(404);
        }

        return Storage::disk('public')->download(
            $attachment->file_path,
            $attachment->original_name
        );
    }

    public function destroy($attachmentPk)
    {
        $attachment = PostAttachment::query()
            ->with(['post.channel.channelUserRoles'])
            ->where('pk', $attachmentPk)
            ->firstOrFail();

        if (!$this->canManageAttachment($attachment)) {
            abort(403);
        }

        $postPk = $attachment->post_pk;

        if (Storage::disk('public')->exists($attachment->file_path)) {
            Storage::disk('public')->delete($attachment->file_path);
        }

        $attachment->delete();

        return redirect()
            ->route('posts.show', $postPk)
            ->with('success', '첨부파일이 삭제되었습니다.');
    }

    private function canManageAttachment(PostAttachment $attachment)
    {
        $user = Auth::user();

        if (!$user) {
            return false;
        }

        if ((int) $user->pk === (int) $attachment->post->user_pk) {
            return true;
        }

        return $user->canManageChannel($attachment->post->channel);
    }
}
