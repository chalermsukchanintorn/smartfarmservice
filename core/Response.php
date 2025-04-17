<?php
//----------------------------------------------------------------
//ไฟล์สำหรับจัดการกับการส่งกลับ response ไปยัง client (Web/Mobile/IoT)
//----------------------------------------------------------------

class Response {
    public static function sendResponse($status, $data) {
        http_response_code($status);
        echo json_encode($data);
        exit;
    }
}
?>
