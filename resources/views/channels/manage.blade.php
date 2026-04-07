@extends('layouts.app')

@section('title', $channel->name . ' 채널 관리')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/channel-manage.css') }}">
@endsection

@section('content')
    <div class="channel-manage-page">
        <section class="channel-manage-card">
            <div class="channel-manage-header">
                <div>
                    <h2>채널 관리</h2>
                    <p class="channel-manage-description">
                        채널 관리자와 권한 현황을 관리할 수 있습니다.
                    </p>
                </div>

                <a href="{{ route('channels.show', $channel->pk) }}" class="btn-secondary">
                    채널로 돌아가기
                </a>
            </div>
        </section>

        <section class="channel-manage-card">
            <div class="channel-manage-section-header">
                <h3 class="channel-manage-section-title">채널 기본 정보</h3>

                @if (!$canUpdate)
                    <span class="channel-manage-readonly-text">조회 전용</span>
                @endif
            </div>

            @if ($canUpdate)
                <form method="POST" action="{{ route('channels.manage.update', $channel->pk) }}" class="channel-manage-form">
                    @csrf
                    @method('PUT')

                    <div class="channel-manage-info-grid">
                        <div class="channel-manage-info-item">
                            <label for="name"><strong>채널명</strong></label>
                            <input
                                type="text"
                                name="name"
                                id="name"
                                class="channel-manage-input"
                                value="{{ old('name', $channel->name) }}"
                                maxlength="100"
                            >
                        </div>

                        <div class="channel-manage-info-item">
                            <strong>소유자</strong>
                            <span>{{ $ownerLoginId ? $ownerLoginId : '-' }}</span>
                        </div>

                        <div class="channel-manage-info-item full">
                            <label for="description"><strong>채널 소개</strong></label>
                            <textarea
                                name="description"
                                id="description"
                                class="channel-manage-textarea"
                                rows="5"
                                maxlength="1000"
                            >{{ old('description', $channel->description) }}</textarea>
                        </div>
                    </div>

                    <div class="channel-manage-submit-wrap">
                        <button type="submit" class="btn-basic">채널 정보 저장</button>
                    </div>
                </form>
            @else
                <div class="channel-manage-info-grid">
                    <div class="channel-manage-info-item">
                        <strong>채널명</strong>
                        <span>{{ $channel->name }}</span>
                    </div>

                    <div class="channel-manage-info-item">
                        <strong>소유자</strong>
                        <span>{{ $ownerLoginId ? $ownerLoginId : '-' }}</span>
                    </div>

                    <div class="channel-manage-info-item full">
                        <strong>채널 소개</strong>
                        <span>{{ $channel->description ?: '채널 소개가 아직 등록되지 않았습니다.' }}</span>
                    </div>
                </div>
            @endif
        </section>

        <section class="channel-manage-card">
            <div class="channel-manage-section-header">
                <h3 class="channel-manage-section-title">카테고리 관리</h3>

                @if (!$canUpdate)
                    <span class="channel-manage-readonly-text">조회 전용</span>
                @endif
            </div>

            @if ($canUpdate)
                <form method="POST" action="{{ route('channels.categories.store', $channel->pk) }}" class="channel-manage-form">
                    @csrf

                    <div class="channel-manage-form-row">
                        <label for="new_category_name">새 카테고리</label>
                        <input
                            type="text"
                            name="name"
                            id="new_category_name"
                            class="channel-manage-input category-name-input"
                            maxlength="30"
                            placeholder="카테고리명을 입력해주세요."
                        >
                        <button type="submit" class="btn-basic">카테고리 추가</button>
                    </div>
                </form>
            @endif

            <div class="channel-manage-category-list">
                @forelse ($categories as $category)
                    <div class="channel-manage-category-item">
                        @if ($canUpdate)
                            <form
                                method="POST"
                                action="{{ route('channels.categories.update', array('channelPk' => $channel->pk, 'categoryPk' => $category->pk)) }}"
                                class="channel-manage-category-form"
                            >
                                @csrf
                                @method('PUT')

                                <input
                                    type="text"
                                    name="name"
                                    class="channel-manage-input"
                                    value="{{ $category->name }}"
                                    maxlength="30"
                                >

                                <button type="submit" class="btn-secondary">이름 변경</button>
                            </form>

                            <form
                                method="POST"
                                action="{{ route('channels.categories.destroy', array('channelPk' => $channel->pk, 'categoryPk' => $category->pk)) }}"
                                class="channel-manage-inline-form"
                                onsubmit="return confirm('카테고리를 삭제하시겠습니까?');"
                            >
                                @csrf
                                @method('DELETE')
                                <button
                                    type="submit"
                                    class="channel-manage-remove-button"
                                    {{ $category->name === '일반' ? 'disabled' : '' }}
                                >
                                    삭제
                                </button>
                            </form>
                        @else
                            <div class="channel-manage-category-readonly">
                                <span class="channel-manage-badge">{{ $category->name }}</span>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="channel-manage-empty-box">
                        카테고리가 없습니다.
                    </div>
                @endforelse
            </div>

            <div class="channel-manage-help-text">
                기본 카테고리 '일반' 및 게시글이 연결된 카테고리는 삭제할 수 없습니다.
            </div>
        </section>

        <section class="channel-manage-card">
            <h3 class="channel-manage-section-title">현재 관리자</h3>

            <div class="channel-manage-role-box">
                <div class="channel-manage-role-item">
                    <strong>소유자</strong>
                    <span>{{ $ownerLoginId ? $ownerLoginId : '-' }}</span>
                </div>

                <div class="channel-manage-role-item">
                    <strong>관리자</strong>

                    @if (!empty($managerRoles))
                        <div class="channel-manage-manager-list">
                            @foreach ($managerRoles as $managerRole)
                                <div class="channel-manage-manager-badge">
                                    <span class="channel-manage-badge">
                                        {{ $managerRole['login_id'] }}
                                    </span>

                                    @if ($canUpdate)
                                        <form
                                            method="POST"
                                            action="{{ route('channels.managers.destroy', array('channelPk' => $channel->pk, 'userPk' => $managerRole['user_pk'])) }}"
                                            class="channel-manage-inline-form"
                                            onsubmit="return confirm('해당 관리자를 삭제하시겠습니까?');"
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="channel-manage-remove-button">
                                                삭제
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <span>-</span>
                    @endif
                </div>
            </div>
        </section>

        <section class="channel-manage-card">
            <div class="channel-manage-section-header">
                <h3 class="channel-manage-section-title">관리자 임명</h3>

                @if (!$canUpdate)
                    <span class="channel-manage-readonly-text">조회 전용</span>
                @endif
            </div>

            @if ($canUpdate)
                <form method="POST" action="{{ route('channels.managers.store', $channel->pk) }}" class="channel-manage-form">
                    @csrf

                    <div class="channel-manage-form-row">
                        <label for="user_pk">사용자 선택</label>

                        <select name="user_pk" id="user_pk" class="channel-manage-select">
                            <option value="">사용자를 선택해주세요.</option>

                            @foreach ($assignableUsers as $assignableUser)
                                <option value="{{ $assignableUser->pk }}">
                                    {{ $assignableUser->login_id }}
                                </option>
                            @endforeach
                        </select>

                        <button type="submit" class="btn-basic">관리자 추가</button>
                    </div>
                </form>

                @if ($assignableUsers->isEmpty())
                    <div class="channel-manage-empty-box">
                        추가 가능한 사용자가 없습니다.
                    </div>
                @endif
            @else
                <div class="channel-manage-empty-box">
                    관리자 추가/삭제 권한이 없습니다.
                </div>
            @endif
        </section>
    </div>
@endsection
