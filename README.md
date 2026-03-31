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
- PHP 7.4
- Laravel 8.75

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

### Docker 속도 이슈
bind mount로 인해 로컬보다 약간 느릴 수 있음

### 권한 문제
```bash
docker compose exec app chmod -R 775 storage bootstrap/cache
docker compose exec app chown -R www-data:www-data storage bootstrap/cache
```

---

## 📄 상세 설계 문서

docs/SPEC.md 참고
