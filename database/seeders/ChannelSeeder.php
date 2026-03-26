<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Channel;
use App\Models\ChannelUserRole;
use App\Models\User;
use Illuminate\Database\Seeder;

class ChannelSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::query()->where('login_id', 'admin')->first();
        $user01 = User::query()->where('login_id', 'user01')->first();
        $user02 = User::query()->where('login_id', 'user02')->first();
        
        $channels = [
            [
                'name' => '자유게시판',
                'description' => '가벼운 일상 이야기와 자유로운 주제로 소통하는 채널입니다.',
                'created_user_pk' => $admin->pk,
                'manager_user_pk' => $admin->pk,
                'categories' => ['일반', '잡담', '추천'],
            ],
            [
                'name' => '개발 공부',
                'description' => 'Laravel, PHP, Docker, DB 설계 등 개발 학습 내용을 공유하는 채널입니다.',
                'created_user_pk' => $user01->pk,
                'manager_user_pk' => $user01->pk,
                'categories' => ['일반', 'Laravel', 'PHP', 'Docker'],
            ],
            [
                'name' => '프로젝트 공유',
                'description' => '개인 프로젝트, 포트폴리오, 작업 중인 기능을 공유하고 피드백을 나누는 채널입니다.',
                'created_user_pk' => $user02->pk,
                'manager_user_pk' => $user02->pk,
                'categories' => ['일반', '기획', '구현', '회고'],
            ],
            [
                'name' => '질문과 답변',
                'description' => '기술 질문, 구조 설계 고민, 개발 관련 질문과 답변이 오가는 채널입니다.',
                'created_user_pk' => $admin->pk,
                'manager_user_pk' => $admin->pk,
                'categories' => ['일반', '질문', '해결'],
            ],
            [
                'name' => '공지사항',
                'description' => '서비스 운영 공지, 점검 안내, 정책 변경 사항을 공유하는 채널입니다.',
                'created_user_pk' => $admin->pk,
                'manager_user_pk' => $admin->pk,
                'categories' => ['일반', '운영공지'],
            ],
            [
                'name' => '취미 공유',
                'description' => '음악, 운동, 영화, 카페 등 취미와 일상 이야기를 나누는 채널입니다.',
                'created_user_pk' => $user01->pk,
                'manager_user_pk' => $user01->pk,
                'categories' => ['일반', '음악', '운동', '카페'],
            ],
        ];
        
        foreach ($channels as $channelData) {
            $channel = Channel::query()->updateOrCreate(
                ['name' => $channelData['name']],
                [
                    'description' => $channelData['description'],
                    'status' => 'active',
                    'created_user_pk' => $channelData['created_user_pk'],
                ]
            );
            
            ChannelUserRole::query()->updateOrCreate(
                [
                    'channel_pk' => $channel->pk,
                    'user_pk' => $channelData['manager_user_pk'],
                ],
                [
                    'role' => ChannelUserRole::ROLE_OWNER,
                ]
            );
            
            foreach ($channelData['categories'] as $index => $categoryName) {
                Category::query()->updateOrCreate(
                    [
                        'channel_pk' => $channel->pk,
                        'name' => $categoryName,
                    ],
                    [
                        'sort_order' => $index,
                    ]
                );
            }
        }
    }
}