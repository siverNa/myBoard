<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Channel;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::query()->where('login_id', 'admin')->first();
        $user01 = User::query()->where('login_id', 'user01')->first();
        $user02 = User::query()->where('login_id', 'user02')->first();
        
        $authors = [$admin, $user01, $user02];
        
        $postTemplates = [
            '자유게시판' => [
                '오늘 점심 뭐 드셨나요?',
                '주말에 다녀온 곳 추천합니다',
                '퇴근 후에 하는 소소한 취미',
                '요즘 자주 듣는 음악 공유해요',
                '최근 본 영화 후기 남깁니다',
                '출근길에 있었던 일',
            ],
            '개발 공부' => [
                'Laravel 라우팅 개념 정리',
                'Eloquent 관계를 이해해보자',
                'Docker 기본 개념 복습',
                'PHP 8 문법에서 익숙해져야 할 것들',
                'Blade 템플릿 정리',
                '마이그레이션 설계 시 고려한 점',
            ],
            '프로젝트 공유' => [
                'myBoard 메인 화면 초안 공유',
                '채널 상세 페이지 구조 정리',
                '게시글 삭제 정책 설계 공유',
                '권한 구조에 대한 고민',
                '포트폴리오 README 정리 중입니다',
                '카테고리 구조 설계 회고',
            ],
            '질문과 답변' => [
                'hasMany와 belongsTo 차이가 뭔가요?',
                'route name은 왜 쓰는 건가요?',
                '컨트롤러와 서비스의 역할을 어떻게 나누나요?',
                '게시글 목록 정렬 기준은 어떻게 잡는 게 좋을까요?',
                '카테고리 필터링 구현 질문',
            ],
            '공지사항' => [
                '서비스 점검 예정 안내',
                '채널 운영 정책 안내',
                '게시판 이용 가이드 공지',
                '관리자 삭제 정책 변경 안내',
            ],
            '취미 공유' => [
                '최근 다녀온 카페 추천',
                '운동 루틴 기록 공유',
                '집중할 때 듣는 음악 플레이리스트',
                '주말 산책 코스 추천',
                '요즘 즐겨보는 콘텐츠 추천',
            ],
        ];
        
        foreach ($postTemplates as $channelName => $titles) {
            $channel = Channel::query()->where('name', $channelName)->first();
            
            if (!$channel) {
                continue;
            }
            
            $categories = Category::query()
                ->where('channel_pk', $channel->pk)
                ->orderBy('sort_order')
                ->get();
            
            foreach ($titles as $index => $title) {
                $author = $authors[$index % count($authors)];
                $category = $categories[$index % $categories->count()];
                
                Post::query()->create([
                    'channel_pk' => $channel->pk,
                    'category_pk' => $category->pk,
                    'user_pk' => $author->pk,
                    'title' => $title,
                    'content' => $title . '에 대한 샘플 본문입니다. myBoard 포트폴리오 프로젝트에서 화면과 기능 확인을 위해 등록된 테스트 게시글입니다.',
                    'view_count' => rand(0, 150),
                    'is_hidden' => false,
                    'created_at' => now()->subDays(rand(0, 20))->subMinutes(rand(0, 1440)),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}