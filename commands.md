# 구축 명령어 기록

Rocky Linux 9.8 서버에 직접 실행한 명령어를 순서대로 정리했습니다.
(비밀번호는 ******** 로 가렸습니다)

## 1. 서버 접속과 기본 확인
```
ssh admin@172.30.1.25     # 윈도우에서 서버로 SSH 접속
whoami                    # 현재 사용자 확인
pwd                       # 현재 위치 확인
ip a                      # 서버 IP 확인
```

## 2. 웹서버(Nginx) 설치와 실행
```
sudo dnf install -y nginx
sudo systemctl enable --now nginx    # 지금 켜고, 재부팅 후에도 자동 시작
systemctl status nginx               # active (running) 확인
curl localhost                       # 서버 내부에서 웹 응답 확인
```

## 3. 방화벽 설정 (장애 1 해결)
```
sudo firewall-cmd --list-all                          # 허용 목록 확인 (http 없음)
sudo firewall-cmd --permanent --add-service=http      # http 영구 추가
sudo firewall-cmd --reload                            # 설정 적용
```

## 4. 데이터베이스(MariaDB) 설치와 실행
```
sudo dnf install -y mariadb-server
sudo systemctl enable --now mariadb
systemctl is-active mariadb
sudo mariadb                  # DB 접속
```

DB 안에서 실행:
```
CREATE DATABASE portfolio;
USE portfolio;
CREATE TABLE guestbook (name VARCHAR(50));
INSERT INTO guestbook VALUES ('hello');
SELECT * FROM guestbook;
```

## 5. PHP 설치와 Nginx 연동
```
sudo dnf install -y php-fpm php-mysqlnd
sudo systemctl enable --now php-fpm
sudo systemctl restart nginx
```

## 6. 웹 전용 DB 계정 (최소 권한)
DB 안에서 실행:
```
CREATE USER 'webuser'@'localhost' IDENTIFIED BY '********';
GRANT ALL PRIVILEGES ON portfolio.* TO 'webuser'@'localhost';
FLUSH PRIVILEGES;
```
확인:
```
mariadb -u webuser -p
SHOW DATABASES;          # portfolio, information_schema 만 보임
```

## 7. 장애 확인용 로그 명령어 (장애 2 해결)
```
sudo tail -n 5 /var/log/nginx/error.log
sudo tail -n 5 /var/log/php-fpm/www-error.log   # Parse error 확인
```

## 8. 운영 상태 확인
```
systemctl is-active nginx php-fpm mariadb
sudo firewall-cmd --list-all
```
