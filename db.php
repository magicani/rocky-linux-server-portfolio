<?php
// 웹에서 MariaDB(portfolio DB)의 guestbook 표를 읽어 출력하는 예제
// 실제 비밀번호는 보안상 ******** 로 가려서 올렸습니다.
$conn = new mysqli('localhost', 'webuser', '********', 'portfolio');
if ($conn->connect_error) { die('DB connect failed'); }
$result = $conn->query('SELECT name FROM guestbook');
while ($row = $result->fetch_assoc()) { echo $row['name'] . '<br>'; }
?>
