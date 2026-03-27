<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Channel;
use App\Models\ChannelRequest;
use App\Models\ChannelUserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChannelRequestController extends Controller
{
    public function create()
    {
        return view('channel-requests.create');
    }
    
    public function store(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'channel_name' => 'required|string|max:191',
            'channel_description' => 'nullable|string',
            'reason' => 'required|string',
        ]);
        
        $duplicatePendingRequest = ChannelRequest::query()
            ->where('applicant_user_pk', $user->pk)
            ->where('channel_name', $request->channel_name)
            ->where('status', ChannelRequest::STATUS_PENDING)
            ->exists();
        
        if ($duplicatePendingRequest) {
            return back()
                ->withErrors([
                    'channel_name' => '같은 이름의 채널 개설 신청이 이미 대기 중입니다.',
                ])
                ->withInput();
        }
        
        $channelAlreadyExists = Channel::query()
            ->where('name', $request->channel_name)
            ->exists();
        
        if ($channelAlreadyExists) {
            return back()
            ->withErrors([
                'channel_name' => '이미 존재하는 채널명입니다.',
            ])
            ->withInput();
        }
        
        ChannelRequest::query()->create([
            'applicant_user_pk' => $user->pk,
            'channel_name' => $request->channel_name,
            'channel_description' => $request->channel_description,
            'reason' => $request->reason,
            'status' => ChannelRequest::STATUS_PENDING,
        ]);
        
        return redirect()
            ->route('home')
            ->with('success', '채널 개설 신청이 접수되었습니다.');
    }
    
    public function index()
    {
        $user = Auth::user();
        
        if (!$user || !$user->isSuperAdmin()) {
            abort(403);
        }
        
        $channelRequests = ChannelRequest::query()
            ->with(['applicant', 'reviewer'])
            ->orderByRaw("
                CASE
                    WHEN status = 'pending' THEN 0
                    ELSE 1
                END
            ")
            ->orderByRaw("
                CASE
                    WHEN status = 'pending' THEN pk
                    ELSE 0
                END DESC
            ")
            ->orderByRaw("
                CASE
                    WHEN status <> 'pending' THEN reviewed_at
                    ELSE NULL
                END DESC
            ")
            ->orderByDesc('pk')
            ->get();
            
            return view('channel-requests.index', compact('channelRequests'));
    }
    
    public function approve($requestPk)
    {
        $user = Auth::user();
        
        if (!$user || !$user->isSuperAdmin()) {
            abort(403);
        }
        
        $channelRequest = ChannelRequest::query()
            ->where('pk', $requestPk)
            ->firstOrFail();
        
        if ($channelRequest->status !== ChannelRequest::STATUS_PENDING) {
            return redirect()
                ->route('channel-requests.index')
                ->with('error', '대기 중인 신청만 승인할 수 있습니다.');
        }
        
        $channelAlreadyExists = Channel::query()
            ->where('name', $channelRequest->channel_name)
            ->exists();
        
        if ($channelAlreadyExists) {
            return redirect()
                ->route('channel-requests.index')
                ->with('error', '이미 같은 이름의 채널이 존재하여 승인할 수 없습니다.');
        }
        
        $channel = Channel::query()->create([
            'name' => $channelRequest->channel_name,
            'description' => $channelRequest->channel_description,
            'status' => 'active',
            'created_user_pk' => $channelRequest->applicant_user_pk,
        ]);
        
        ChannelUserRole::query()->create([
            'channel_pk' => $channel->pk,
            'user_pk' => $channelRequest->applicant_user_pk,
            'role' => ChannelUserRole::ROLE_OWNER,
        ]);
        // 채널 카테고리 기본값 생성
        Category::query()->create([
            'channel_pk' => $channel->pk,
            'name' => '일반',
            'sort_order' => 0,
        ]);
        
        $channelRequest->update([
            'status' => ChannelRequest::STATUS_APPROVED,
            'reviewed_user_pk' => $user->pk,
            'reviewed_at' => now(),
            'reject_reason' => null,
        ]);
        
        return redirect()
            ->route('channel-requests.index')
            ->with('success', '채널 개설 신청이 승인되었습니다.');
    }
    
    public function reject(Request $request, $requestPk)
    {
        $user = Auth::user();
        
        if (!$user || !$user->isSuperAdmin()) {
            abort(403);
        }
        
        $channelRequest = ChannelRequest::query()
            ->where('pk', $requestPk)
            ->firstOrFail();
        
        if ($channelRequest->status !== ChannelRequest::STATUS_PENDING) {
            return redirect()
                ->route('channel-requests.index')
                ->with('error', '대기 중인 신청만 반려할 수 있습니다.');
        }
        
        $request->validate([
            'reject_reason' => 'required|string',
        ]);
        
        $channelRequest->update([
            'status' => ChannelRequest::STATUS_REJECTED,
            'reviewed_user_pk' => $user->pk,
            'reviewed_at' => now(),
            'reject_reason' => $request->reject_reason,
        ]);
        
        return redirect()
            ->route('channel-requests.index')
            ->with('success', '채널 개설 신청이 반려되었습니다.');
    }
}