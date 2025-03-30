<?php
require_once "./../config/Database.php"; //จัดการติดต่อกับฐานข้อมูล
require_once "./../models/Sensor.php"; //จัดการการทำงาน CRUD กับตารางในฐานข้อมูล
require_once "./../core/Response.php"; //จัดการการส่งค่ากลับไปยัง client (Web/Mobile/IoT)

class SensorController {
    private $db;
    private $sensor;

    public function __construct() {
        $this->db = new Database(); //สร้างตัวแปร $db เพื่อเชื่อมต่อกับฐานข้อมูล
        $this->sensor = new Sensor($this->db->connect()); //สร้างตัวแปร $sensor เพื่อทำงาน CRUD กับตารางในฐานข้อมูล
    }

    //ฟังก์ชันการทำงาน (CRUD) ในส่วนของ Controller กับฝั่ง client (Web/Mobile/IoT)
    public function getSensorAll() {
        $result = $this->sensor->getSensorAll(); //ดึงข้อมูล Sensor ทั้งหมดจาก Sensor_tb
        $this->sendResponseFromResult($result); //ใชแบบนี้ก็กรณีที่การส่งค่ากลับมีมากกว่า 1 รายการ
    }

    public function getSensorAllByDate($date) {
        $result = $this->sensor->getSensorAllByDate($date); 
        $this->sendResponseFromResult($result);
    }

    public function getSensorAllByDateAndBetweenTime($date, $start_time, $end_time){
        $result = $this->sensor->getSensorAllByDateAndBetweenTime($date, $start_time, $end_time); 
        $this->sendResponseFromResult($result);        
    }

    public function getSensorAllByBetweenDate($start_date, $end_date){
        $result = $this->sensor->getSensorAllByBetweenDate($start_date, $end_date); 
        $this->sendResponseFromResult($result);
    }

    public function getSensorTempByDate($date) {
        $result = $this->sensor->getSensorTempByDate($date); 
        $this->sendResponseFromResult($result);
    }

    //ใช้ในกรณีที่การส่งค่ากลับมีมากกว่า 1 รายการ/แถว/เรคอร์ด/ข้อมูล
    private function sendResponseFromResult($result) {
        $num = $result->rowCount();
        if ($num > 0) {
            $sensors_arr = array("message" => "ok", "data" => array());
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $sensors_arr["data"][] = $row;
            }
            Response::sendResponse(200, $sensors_arr);
        } else {
            Response::sendResponse(404, ["message" => "No data found"]);
        }
    }
}