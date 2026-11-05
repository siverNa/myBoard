# myBoard

## 📌 프로젝트 소개
myBoard는 채널 기반 게시판 시스템으로,
사용자들이 채널 내에서 게시글을 작성하고 카테고리별로 관리할 수 있는 웹 애플리케이션입니다.

단순 CRUD를 넘어서,
첨부파일 관리, 삭제 정책 분리, 채널 구조 설계 등을 포함하여
실제 서비스 형태를 고려하여 구현했습니다.

---

## 🛠 기술 스택

### Backend
- PHP 8.3
- Laravel 13

### Database
- MySQL 5.7

### Infra / DevOps
- Docker
- Docker Compose
- Nginx + PHP-FPM

---

## 🏗 시스템 구조

[ Nginx ] → [ PHP-FPM ] → [ MySQL ]
                     ↓
               [ phpMyAdmin ]

---

## 🚀 실행 방법

```bash
git clone <repository-url>
cd myBoard

docker compose up -d --build
```

초기 세팅:

```bash
docker compose exec app composer install
docker compose exec app cp .env.example .env
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate
docker compose exec app php artisan db:seed
docker compose exec app php artisan storage:link
```

---

## 🌐 접속 정보

- 웹: http://localhost:8088
- phpMyAdmin: http://localhost:8089

DB 정보:
- Host: db
- Port: 3306
- DB: myboard
- User: myboard_user
- Password: myboard_pass

---

## 📂 주요 기능

### 채널 기반 게시판
- 게시글은 특정 채널 내부에 속함
- 채널 생성 시 기본 카테고리 "일반" 자동 생성

### 게시글 CRUD
- 게시글 생성 / 조회 / 수정 / 삭제
- 현재 채널 기준으로 게시글 관리

### 첨부파일 관리
- 다중 파일 업로드 지원
- storage 기반 파일 저장

### 삭제 정책 분리
- 사용자 삭제: 실제 삭제 (DB + 파일)
- 관리자 삭제: 숨김 처리 (is_hidden)

### 관리자 구조
- 채널 소유자(owner) 표시
- 관리자(manager)는 hover 시 목록 표시

---

## ⚙️ 설계 특징

### 기본키 pk 사용
- 모든 테이블의 기본키를 id 대신 pk로 통일

### 채널 중심 구조
- 게시글은 채널에 종속

### 파일 처리 분리
- DB와 파일 storage 분리

### Docker 기반 실행 환경
- 동일한 환경에서 실행 가능

---

## ⚠️ 트러블슈팅

### 포트 충돌
포트가 이미 사용 중일 경우 docker-compose 포트 변경

### 권한 문제
```bash
docker compose exec app chmod -R 775 storage bootstrap/cache
docker compose exec app chown -R www-data:www-data storage bootstrap/cache
```

---

## 📄 상세 설계 문서

docs/SPEC.md 참고

---
## 추가내용
## 🔄 기술 환경 업그레이드

최근 PHP 보안 이슈가 발생하였습니다.
초기 PHP 7.4 및 Laravel 8 기반으로 개발한 프로젝트를 보안 및 유지보수 측면에서 개선하기 위해 단계적으로 업그레이드했습니다.

### 업그레이드 이력

| 항목 | 기존 버전 | 변경 버전 |
|---|---|---|
| PHP | 7.4 | 8.3 |
| Laravel | 8 | 13 |

### 주요 작업 내용

- PHP 런타임 버전 업그레이드
- Laravel 메이저 버전 단계별 업그레이드 (8 → 9 → 10 → 11 → 12 → 13)
- 버전별 Composer 의존성 충돌 해결 및 패키지 업데이트
- Laravel 버전 변경에 따른 미들웨어 및 설정 호환성 수정
- Docker 실행 환경 재빌드 및 검증
- PHPUnit 버전 업그레이드

### 검증 결과

- 회원가입, 로그인 및 로그아웃 정상 동작 확인
- 게시글 및 댓글 CRUD 정상 동작 확인
- 채널 및 관리자 권한 기능 정상 동작 확인
- 통계 및 파일 업로드 기능 정상 동작 확인

업그레이드 이후에도 기존 서비스 기능이 정상적으로 동작하는 것을 확인했습니다.
