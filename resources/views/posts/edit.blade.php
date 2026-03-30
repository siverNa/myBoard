@extends('layouts.app')

@section('title', '게시글 수정')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/post.css') }}">
@endsection

@section('content')
    <div class="post-form-page">
        <section class="post-form-card">
            <div class="post-form-header">
                <div>
                    <h2>게시글 수정</h2>
                    <p>
                        현재 채널: <strong>{{ $post->channel->name }}</strong>
                    </p>
                </div>

                <a href="{{ route('posts.show', $post->pk) }}" class="post-back-link">
                    게시글 상세로 돌아가기
                </a>
            </div>

            @if ($errors->any())
                <div class="post-form-error-box">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('posts.update', $post->pk) }}" class="post-form" enctype="multipart/form-data" id="postEditForm">
                @csrf
                @method('PUT')

                <div class="post-form-field">
                    <label for="category_pk">카테고리</label>
                    <select name="category_pk" id="category_pk">
                        <option value="">카테고리를 선택하세요.</option>
                        @foreach ($post->channel->categories as $category)
                            <option value="{{ $category->pk }}" {{ (string) old('category_pk', $post->category_pk) === (string) $category->pk ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="post-form-field">
                    <label for="title">제목</label>
                    <input
                        type="text"
                        name="title"
                        id="title"
                        value="{{ old('title', $post->title) }}"
                        maxlength="255"
                    >
                </div>

                <div class="post-form-field">
                    <label for="content">내용</label>
                    <textarea
                        name="content"
                        id="content"
                        rows="12"
                    >{{ old('content', $post->content) }}</textarea>
                </div>

                <div class="post-form-field">
                    <label>기존 첨부파일</label>
                
                    @if ($post->attachments->isNotEmpty())
                        <div class="post-existing-attachments">
                            <div class="post-existing-attachment-list">
                                @foreach ($post->attachments as $attachment)
                                    <div class="post-existing-attachment-item">
                                        <div class="post-existing-attachment-left">
                                            <a href="{{ route('attachments.download', $attachment->pk) }}">
                                                {{ $attachment->original_name }}
                                            </a>
                                            <span>{{ number_format($attachment->file_size / 1024, 1) }} KB</span>
                                        </div>
                
                                        <button
                                            type="submit"
                                            form="delete-attachment-{{ $attachment->pk }}"
                                            class="post-existing-attachment-delete-button"
                                            onclick="return confirm('첨부파일을 삭제하시겠습니까?');"
                                        >삭제</button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="post-existing-attachments-empty">
                            현재 첨부된 파일이 없습니다.
                        </div>
                    @endif
                </div>
                
                <div class="post-form-field post-attachment-upload-field">
                    <label for="attachments">새 이미지 추가</label>
                    <input
                        type="file"
                        name="attachments[]"
                        id="attachments"
                        accept=".jpg,.jpeg,.png,.gif,.webp,image/jpeg,image/png,image/gif,image/webp"
                        multiple
                    >
                    <small class="post-form-help">
                        jpg, jpeg, png, gif, webp 파일만 업로드할 수 있습니다. 최대 5MB
                    </small>
                </div>

                <div class="post-form-actions">
                    <button type="submit" class="btn-basic">수정하기</button>
                    <a href="{{ route('posts.show', $post->pk) }}" class="btn-secondary">취소</a>
                </div>
            </form>

            @foreach ($post->attachments as $attachment)
                <form
                    id="delete-attachment-{{ $attachment->pk }}"
                    method="POST"
                    action="{{ route('attachments.destroy', $attachment->pk) }}"
                    style="display: none;"
                >
                    @csrf
                    @method('DELETE')
                </form>
            @endforeach
        </section>
    </div>
@endsection

<script>
document.addEventListener('DOMContentLoaded', function () {
    var form = document.getElementById('postEditForm');
    var titleInput = document.getElementById('title');
    var contentTextarea = document.getElementById('content');

    if (!form || !titleInput || !contentTextarea) {
        return;
    }

    var storageKeyPrefix = 'post-edit-draft-{{ $post->pk }}';
    var titleKey = storageKeyPrefix + '-title';
    var contentKey = storageKeyPrefix + '-content';

    var savedTitle = sessionStorage.getItem(titleKey);
    var savedContent = sessionStorage.getItem(contentKey);

    if (savedTitle !== null && savedTitle !== '') {
        titleInput.value = savedTitle;
    }

    if (savedContent !== null && savedContent !== '') {
        contentTextarea.value = savedContent;
    }

    // 세션 스토리지에 입력된 제목, 내용을 임시 저장
    function saveDraft() {
        sessionStorage.setItem(titleKey, titleInput.value);
        sessionStorage.setItem(contentKey, contentTextarea.value);
    }

    titleInput.addEventListener('input', saveDraft);
    contentTextarea.addEventListener('input', saveDraft);

    // 수정 시 저장했던 임시 제목, 내용 값 제거
    form.addEventListener('submit', function () {
        sessionStorage.removeItem(titleKey);
        sessionStorage.removeItem(contentKey);
    });

    // 업로드된 파일들 삭제 버튼 클릭 시, 제목 및 내용 임시 저장
    var deleteButtons = document.querySelectorAll('.post-existing-attachment-delete-button');
    for (var i = 0; i < deleteButtons.length; i++) {
        deleteButtons[i].addEventListener('click', function () {
            saveDraft();
        });
    }
});
</script>