<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use App\Models\ChannelUserRole;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use App\Models\Post;

class ChannelManageController extends Controller
{
    public function index($channelPk)
    {
        $channel = Channel::query()
            ->with(['channelUserRoles.user'])
            ->where('pk', $channelPk)
            ->firstOrFail();

        $user = Auth::user();

        if (!$user->canManageChannel($channel)) {
            abort(403);
        }

        $ownerLoginId = null;
        $managerRoles = array();

        foreach ($channel->channelUserRoles as $channelUserRole) {
            if (!$channelUserRole->user) {
                continue;
            }

            if ($channelUserRole->role === ChannelUserRole::ROLE_OWNER) {
                $ownerLoginId = $channelUserRole->user->login_id;
            }

            if ($channelUserRole->role === ChannelUserRole::ROLE_MANAGER) {
                $managerRoles[] = array(
                    'user_pk' => $channelUserRole->user->pk,
                    'login_id' => $channelUserRole->user->login_id,
                );
            }
        }

        $excludedUserPks = $channel->channelUserRoles
            ->pluck('user_pk')
            ->filter()
            ->unique()
            ->values()
            ->toArray();

        $assignableUsers = User::query()
            ->whereNotIn('pk', $excludedUserPks)
            ->orderBy('login_id')
            ->get();

        $canUpdate = $this->canUpdateChannelManagers($user, $channel);

        $categories = $channel->categories()
            ->orderBy('pk')
            ->get();

        return view('channels.manage', compact(
            'channel',
            'ownerLoginId',
            'managerRoles',
            'assignableUsers',
            'canUpdate',
            'categories',
        ));
    }

    public function storeManager(Request $request, $channelPk)
    {
        $channel = $this->checkManager($channelPk, array('channelUserRoles'));

        $userPk = (int) $request->input('user_pk');

        if ($userPk <= 0) {
            return redirect()
                ->route('channels.manage', $channel->pk)
                ->with('error', '관리자로 추가할 사용자를 선택해주세요.');
        }

        $targetUser = User::query()
            ->where('pk', $userPk)
            ->first();

        if (!$targetUser) {
            return redirect()
                ->route('channels.manage', $channel->pk)
                ->with('error', '선택한 사용자를 찾을 수 없습니다.');
        }

        $alreadyAssigned = ChannelUserRole::query()
            ->where('channel_pk', $channel->pk)
            ->where('user_pk', $targetUser->pk)
            ->exists();

        if ($alreadyAssigned) {
            return redirect()
                ->route('channels.manage', $channel->pk)
                ->with('error', '이미 owner 또는 manager로 등록된 사용자입니다.');
        }

        ChannelUserRole::query()->create(array(
            'channel_pk' => $channel->pk,
            'user_pk' => $targetUser->pk,
            'role' => ChannelUserRole::ROLE_MANAGER,
        ));

        return redirect()
            ->route('channels.manage', $channel->pk)
            ->with('success', '관리자가 추가되었습니다.');
    }

    public function destroyManager($channelPk, $userPk)
    {
        $channel = $this->checkManager($channelPk, array('channelUserRoles'));

        $targetRole = ChannelUserRole::query()
            ->where('channel_pk', $channel->pk)
            ->where('user_pk', $userPk)
            ->first();

        if (!$targetRole) {
            return redirect()
                ->route('channels.manage', $channel->pk)
                ->with('error', '해당 관리자 정보를 찾을 수 없습니다.');
        }

        if ($targetRole->role === ChannelUserRole::ROLE_OWNER) {
            return redirect()
                ->route('channels.manage', $channel->pk)
                ->with('error', '소유자는 삭제할 수 없습니다.');
        }

        if ($targetRole->role !== ChannelUserRole::ROLE_MANAGER) {
            return redirect()
                ->route('channels.manage', $channel->pk)
                ->with('error', '삭제 가능한 관리자 역할이 아닙니다.');
        }

        $targetRole->delete();

        return redirect()
            ->route('channels.manage', $channel->pk)
            ->with('success', '관리자가 삭제되었습니다.');
    }

    public function update(Request $request, $channelPk)
    {
        $channel = $this->checkManager($channelPk);

        $name = trim((string) $request->input('name', ''));
        $description = trim((string) $request->input('description', ''));

        if ($name === '') {
            return redirect()
                ->route('channels.manage', $channel->pk)
                ->with('error', '채널명을 입력해주세요.');
        }

        if (mb_strlen($name) > 100) {
            return redirect()
                ->route('channels.manage', $channel->pk)
                ->with('error', '채널명은 100자 이하로 입력해주세요.');
        }

        if (mb_strlen($description) > 1000) {
            return redirect()
                ->route('channels.manage', $channel->pk)
                ->with('error', '채널 소개는 1000자 이하로 입력해주세요.');
        }

        $exists = Channel::query()
            ->where('pk', '!=', $channel->pk)
            ->where('name', $name)
            ->exists();

        if ($exists) {
            return redirect()
                ->route('channels.manage', $channel->pk)
                ->with('error', '이미 사용 중인 채널명입니다.');
        }

        $channel->name = $name;
        $channel->description = $description !== '' ? $description : null;
        $channel->save();

        return redirect()
            ->route('channels.manage', $channel->pk)
            ->with('success', '채널 정보가 수정되었습니다.');
    }

    // 카테고리 추가
    public function storeCategory(Request $request, $channelPk)
    {
        $channel = $this->checkManager($channelPk);

        $name = trim((string) $request->input('name', ''));

        if ($name === '') {
            return redirect()
                ->route('channels.manage', $channel->pk)
                ->with('error', '카테고리명을 입력해주세요.');
        }

        if (mb_strlen($name) > 30) {
            return redirect()
                ->route('channels.manage', $channel->pk)
                ->with('error', '카테고리명은 30자 이하로 입력해주세요.');
        }

        $exists = Category::query()
            ->where('channel_pk', $channel->pk)
            ->where('name', $name)
            ->exists();

        if ($exists) {
            return redirect()
                ->route('channels.manage', $channel->pk)
                ->with('error', '이미 존재하는 카테고리명입니다.');
        }

        Category::query()->create(array(
            'channel_pk' => $channel->pk,
            'name' => $name,
        ));

        return redirect()
            ->route('channels.manage', $channel->pk)
            ->with('success', '카테고리가 추가되었습니다.');
    }

    // 카테고리 수정
    public function updateCategory(Request $request, $channelPk, $categoryPk)
    {
        $channel = $this->checkManager($channelPk);

        $category = Category::query()
            ->where('pk', $categoryPk)
            ->where('channel_pk', $channel->pk)
            ->firstOrFail();

        $name = trim((string) $request->input('name', ''));

        if ($name === '') {
            return redirect()
                ->route('channels.manage', $channel->pk)
                ->with('error', '카테고리명을 입력해주세요.');
        }

        if (mb_strlen($name) > 30) {
            return redirect()
                ->route('channels.manage', $channel->pk)
                ->with('error', '카테고리명은 30자 이하로 입력해주세요.');
        }

        $exists = Category::query()
            ->where('channel_pk', $channel->pk)
            ->where('name', $name)
            ->where('pk', '!=', $category->pk)
            ->exists();

        if ($exists) {
            return redirect()
                ->route('channels.manage', $channel->pk)
                ->with('error', '이미 존재하는 카테고리명입니다.');
        }

        $category->name = $name;
        $category->save();

        return redirect()
            ->route('channels.manage', $channel->pk)
            ->with('success', '카테고리가 수정되었습니다.');
    }

    // 카테고리 삭제
    public function destroyCategory($channelPk, $categoryPk)
    {
        $channel = $this->checkManager($channelPk, array('categories'));

        $category = Category::query()
            ->where('pk', $categoryPk)
            ->where('channel_pk', $channel->pk)
            ->firstOrFail();

        if ($category->name === '일반') {
            return redirect()
                ->route('channels.manage', $channel->pk)
                ->with('error', '기본 카테고리 일반은 삭제할 수 없습니다.');
        }

        if ($channel->categories->count() <= 1) {
            return redirect()
                ->route('channels.manage', $channel->pk)
                ->with('error', '최소 1개의 카테고리는 유지해야 합니다.');
        }

        $postExists = Post::query()
            ->where('channel_pk', $channel->pk)
            ->where('category_pk', $category->pk)
            ->exists();

        if ($postExists) {
            return redirect()
                ->route('channels.manage', $channel->pk)
                ->with('error', '게시글이 연결된 카테고리는 삭제할 수 없습니다.');
        }

        $category->delete();

        return redirect()
            ->route('channels.manage', $channel->pk)
            ->with('success', '카테고리가 삭제되었습니다.');
    }

    protected function checkManager($channelPk, array $with = array())
    {
        $query = Channel::query();

        if (!empty($with)) {
            $query->with($with);
        }

        $channel = $query
            ->where('pk', $channelPk)
            ->firstOrFail();

        $user = Auth::user();

        if (!$this->canUpdateChannelManagers($user, $channel)) {
            abort(403);
        }

        return $channel;
    }

    private function canUpdateChannelManagers(User $user, Channel $channel): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $channel->channelUserRoles()
            ->where('user_pk', $user->pk)
            ->where('role', ChannelUserRole::ROLE_OWNER)
            ->exists();
    }
}
