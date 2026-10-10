# 학습 기록

직접 실습하면서 확인한 내용을 정리했습니다.

## 1. 네트워크
내 PC에서 구간별로 연결을 점검했습니다.

| 구간 | 명령어 | 결과 |
|---|---|---|
| 공유기까지 | ping (게이트웨이) | 손실 0%, 0ms |
| 인터넷까지 | ping 8.8.8.8 | 손실 0%, 약 33ms |
| 이름 변환(DNS) | nslookup naver.com | IP 4개 응답 |

- 서브넷 마스크 255.255.255.0(/24): 앞 3덩어리가 같으면 같은 네트워크
- 포트: SSH 22, HTTP 80, HTTPS 443, MariaDB 3306
- TCP는 연결 후 확인하며 전송, UDP는 확인 없이 빠르게 전송
- DHCP는 IP 자동 배정, DNS는 이름을 IP로 변환

## 2. 리눅스 기본 명령어
| 분류 | 명령어 |
|---|---|
| 위치와 이동 | pwd, ls, cd (상대 경로, 절대 경로, ~, ..) |
| 파일 | mkdir, touch, echo, cat, cp, mv, rm |
| 권한 확인 | ls -l 로 rwx(소유자/그룹/나머지) 읽기 |
| 관리 | sudo, dnf install, systemctl, firewall-cmd, curl, tail |

- 일반 사용자(admin)로 접속하고, 필요한 명령만 sudo로 실행
- 서비스는 `systemctl enable --now`로 즉시 실행과 자동 시작을 함께 설정
- 실습용 서버는 VirtualBox 스냅샷으로 복구 지점을 만들어 두었습니다.

## 3. 데이터베이스(MariaDB) 기본
```
CREATE DATABASE / USE / SHOW DATABASES / SHOW TABLES
CREATE TABLE / INSERT / SELECT / DELETE ... WHERE ... LIMIT
CREATE USER / GRANT / FLUSH PRIVILEGES
```
- DB 명령어는 `;`로 끝나야 실행되고, 빠지면 `->`로 입력이 이어짐
- 웹 전용 계정은 portfolio DB에만 권한 부여 (최소 권한 확인: SHOW DATABASES 결과 비교)

## 4. 만난 에러와 해결
| 에러 | 뜻 | 해결 |
|---|---|---|
| command not found | 없는 명령어 (오타) | 철자 확인 |
| ERROR 1064 near '...' | SQL 문법 오류, 표시된 부분 근처 | 철자·문장 확인 |
| ERROR 1054 Unknown column | where에 칸 이름이 잘못 들어감 | 칸 이름으로 조건 작성 |
| 외부 접속 불가 / nginx error | 방화벽, PHP 오타 | 로그와 허용 목록 확인 (README 트러블슈팅 참고)
