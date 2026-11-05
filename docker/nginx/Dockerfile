FROM nginx:stable-alpine

WORKDIR /var/www/html

# Laravel public 디렉터리 복사
COPY public/ /var/www/html/public/

# 업로드 파일 경로 생성
RUN mkdir -p /var/www/html/storage/app/public \
    && ln -sfn ../storage/app/public /var/www/html/public/storage
