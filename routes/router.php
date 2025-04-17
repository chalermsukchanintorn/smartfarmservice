<?php
//----------------------------------------------------------------
//ไฟล์สำหรับกำหนด เส้นทาง (routes) สำหรับการเรียกใช้งาน API (request from client)
//ตามหลักการของ REST API ต้องมีการกำหนด HTTP Method ตามวิธีการทำงานกับตารางในฐานข้อมูล ดังนี้
//POST (เพิ่มข้อมูล), GET (ค้นหา ตรวจสอบ ดึง ดูข้อมูล), PUT (แก้ไขข้อมูล), DELETE (ลบข้อมูล)
//และกำหนดตัว endpoint (route หรือเส้นทาง) ในการเรียกใช้ API จาก client 
//----------------------------------------------------------------

//กำหนด HTTP Headers เพื่อจัดการการตอบสนองของ API
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

//โดยต้องทำงานร่วมกับไฟล์ต่อไปนี้
//ไฟล์ที่ใช้ในการรับการร้องขอมาจาก client : Request.php
//ไฟล์ที่ใช้ในการควบคุมการทำงานกับตารางในฐานข้อมูล และการตอบกลับไปยัง client : XxxxxController.php ต่างๆ
require_once "./../core/Request.php"; 
require_once "./../controllers/SensorController.php";

//ตัวแปรอ้างอิงคลาส SensorController จากไฟล์ SensorController.php เพื่อทำงานกับตาราง sensor_tb
$sensorController = new SensorController(); 

//กำหนด endpoint สำหรับการเรียกใช้งาน API (request from client) เพื่อดึงข้อมูลทั้งหมดจากตาราง sensor_tb
Request::handleRequest("GET", "/smartfarmservice/sensors", function () use ($sensorController) {
    $sensorController->getSensorAll();
});

//กำหนด endpoint สำหรับการเรียกใช้งาน API (request from client) เพื่อดึงข้อมูลทั้งหมดจากตาราง sensor_tb โดยมีเงื่อนไขตามวัน ที่ client ส่งมา
Request::handleRequest("GET", "/smartfarmservice/sensors/date", function () use ($sensorController) {
    $sensorController->getSensorAllByDate($_GET["date"]);
});

//กำหนด endpoint สำหรับการเรียกใช้งาน API (request from client) เพื่อดึงข้อมูลทั้งหมดจากตาราง sensor_tb โดยมีเงื่อนไขตามวันและช่วงเวลา ที่ client ส่งมา
Request::handleRequest("GET", "/smartfarmservice/sensors/dateandtime", function () use ($sensorController) {
    $sensorController->getSensorAllByDateAndBetweenTime($_GET["date"], $_GET["start_time"], $_GET["end_time"]);
});

//กำหนด endpoint สำหรับการเรียกใช้งาน API (request from client) เพื่อดึงข้อมูลทั้งหมดจากตาราง sensor_tb โดยมีเงื่อนไขตามช่วงวัน ที่ client ส่งมา
Request::handleRequest("GET", "/smartfarmservice/sensors/dateanddate", function () use ($sensorController) {
    $sensorController->getSensorAllByBetweenDate( $_GET["start_date"], $_GET["end_date"]);
});

//กำหนด endpoint สำหรับการเรียกใช้งาน API (request from client) เพื่อดึงข้อมูลจากตาราง sensor_tb เฉพาะค่า temperature อย่างเดียว โดยมีเงื่อนไขตามวัน ที่ client ส่งมา
Request::handleRequest("GET", "/smartfarmservice/sensors/datetemp", function () use ($sensorController) {
    $sensorController->getSensorTempByDate($_GET["date"]);
});

//กำหนด endpoint สำหรับการเรียกใช้งาน API (request from client) เพื่อบันทึกข้อมูลลงตาราง sensor_tb
Request::handleRequest("POST", "/smartfarmservice/sensors", function () use ($sensorController) {
    $sensorController->insertSensor($_POST["temperature"], $_POST["humidity"], $_POST["light"], $_POST["pm_value"], $_POST["recorded_date"], $_POST["recorded_time"]);    
});