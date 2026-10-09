# Rocky Linux 서버 구축 프로젝트

가상 서버에 웹서버(Nginx)와 데이터베이스(MariaDB)를 직접 구축하고
PHP로 웹과 DB를 연동한 뒤 장애를 해결한 기록입니다.

## 환경
- VirtualBox 가상 머신 (메모리 4GB CPU 2개 디스크 20GB 브리지 네트워크)
- Rocky Linux 9.8 (Minimal)
- Nginx PHP-FPM MariaDB 10.5

## 구성도
브라우저 → Nginx(80) → PHP-FPM → MariaDB
(관리 접속: SSH 22)
<img width="712" height="192" alt="제목 없는 다이어그램 drawio" src="https://github.com/user-attachments/assets/48e328aa-ba14-4d08-b0e5-70940dc15b79" />


## 구축 내용
1. VirtualBox에 Rocky Linux 설치, 윈도우에서 SSH 원격 접속
2. Nginx 설치 후 서비스 실행 및 자동 시작 등록 (systemctl)
3. 방화벽(firewalld)에서 필요한 서비스만 허용 (ssh, http)
4. PHP-FPM 설치 및 Nginx 연동
5. MariaDB 설치 `portfolio` DB와 `guestbook` 표 생성
6. 웹 전용 DB 계정(webuser) 생성, `portfolio` DB에만 권한 부여 (최소 권한)
7. PHP로 DB의 값을 읽어 브라우저에 출력
[<img width="1309" height="571" alt="image" src="https://github.com/user-attachments/assets/74fee8f1-67d8-49df-a0b9-abea3d2505ca" />
 / <img width="658" height="321" alt="image" src="https://github.com/user-attachments/assets/ce8f6de3-d34a-4eac-9d80-765109162ab6" />
]

## 트러블슈팅

### 1. 외부 PC에서 웹페이지가 열리지 않음
- 증상: 서버 내부(`curl localhost`)는 정상, 외부 PC 브라우저는 접속 불가
- 확인: `ping`은 성공 → 네트워크 구간 정상. `firewall-cmd --list-all`로 확인하니 허용 목록에 `http`가 없음
- 원인: 방화벽이 80번 포트(HTTP)를 허용하지 않음
- 해결: `firewall-cmd --permanent --add-service=http` 후 `--reload`

### 2. PHP 페이지에서 nginx error 발생
- 증상: `test.php` 접속 시 `nginx error!` 페이지
- 확인: Nginx 에러 로그에는 PHP 연결 오류가 없음 → PHP 로그(`/var/log/php-fpm/www-error.log`) 확인
- 원인: PHP 로그에 `Parse error ... on line 1`, 파일 안의 오타
- 해결: 파일을 다시 작성해 정상 출력 확인

## 배운 점
- 장애는 구간(네트워크 방화벽 서비스 로그)별로 나누어 확인한다.
- 서비스 계정에는 필요한 권한만 부여한다.
- 에러 로그의 파일명과 줄 번호로 원인을 빠르게 찾을 수 있다.

## 진행 예정
- SSH 키 로그인 root 접속 차단
- 자동 백업(cron)
- 장애 시나리오 추가 재현
- HTTPS 적용
