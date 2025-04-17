<?php
//----------------------------------------------------------------
//ไฟล์สำหรับจัดการกับการร้องของ request จาก client (Web/Mobile/IoT)
//----------------------------------------------------------------

class Request {
    public static function handleRequest($method, $endpoint, $callback) {
        $requestUri = strtok($_SERVER["REQUEST_URI"], '?');
        $requestMethod = $_SERVER["REQUEST_METHOD"];
             
        if ($requestUri === $endpoint && $requestMethod === $method) {
            $callback();
            exit;
        }
    }
}
?>