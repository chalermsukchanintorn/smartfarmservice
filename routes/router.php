<?php
//ไฟล์นี้ในกำหนดตัว endpoint (route) ในการทำงาน CRUD กับตารางในฐานข้อมูลจาก client (Web/Mobile/IoT)
//ดึงข้อมูลทั้งหมด GET | /smartfarmservice/sensors

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

require_once "./../core/Request.php"; 
require_once "./../controllers/SensorController.php";

//สร้างตัวแปรทีี่ใช้ทำงานกับตัว Controller ของ Sensor
$sensorController = new SensorController();

Request::handleRequest("GET", "/smartfarmservice/sensors", function () use ($sensorController) {
    $sensorController->getSensorAll();
});

Request::handleRequest("GET", "/smartfarmservice/sensors/date", function () use ($sensorController) {
    $sensorController->getSensorAllByDate($_GET["date"]);
});

Request::handleRequest("GET", "/smartfarmservice/sensors/dateandtime", function () use ($sensorController) {
    $sensorController->getSensorAllByDateAndBetweenTime($_GET["date"], $_GET["start_time"], $_GET["end_time"]);
});

Request::handleRequest("GET", "/smartfarmservice/sensors/dateanddate", function () use ($sensorController) {
    $sensorController->getSensorAllByBetweenDate( $_GET["start_date"], $_GET["end_date"]);
});

Request::handleRequest("GET", "/smartfarmservice/sensors/datetemp", function () use ($sensorController) {
    $sensorController->getSensorTempByDate($_GET["date"]);
});