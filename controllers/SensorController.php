<?php
//----------------------------------------------------------------
//ไฟล์สำหรับควบคุมการทำงานกับตารางในฐานข้อมูลจากการเรียกใช้ API (request จาก client)
//และส่งผลลัพธ์จากการทำงานกลับไปยัง client (response กลับไปยัง client)
//ณ ไฟล์นี้คือ ทำงานกับตาราง sensor_tb โดยต้องทำงานร่วมกับไฟล์ต่อไปนี้
//ไฟล์ที่ใช้ติดต่อฐานข้อมูล : Database.php
//ไฟล์ที่ทำงานกับตาราง sensor_tb : Sensor.php
//ไฟล์ที่ใช้ในการตอบกลับไปยัง client : Response.php
//----------------------------------------------------------------

require_once "./../config/Database.php";
require_once "./../models/Sensor.php";
require_once "./../core/Response.php";

class SensorController
{
    //ตัวแปรเพื่ิออ้างอิงคลาส Database จากไฟล์ Database.php เพื่อติดต่อฐานข้อมูล
    private $connectDB;
    //ตัวแปรเพื่ออ้างอิงคลาส Sensor จากไฟล์ Sensor.php เพื่อทำงานกับตาราง sensor_tb
    private $sensor;

    //คอนสตรักเตอร์ที่จะกำหนดค่าให้กับ
    //ตัวแปร $connectDB เพื่อติดต่อฐานข้อมูล 
    //ตัวแปร $sensor เพื่อทำงานกับตาราง sensor_tb
    public function __construct()
    {
        $this->connectDB = new Database();
        $this->sensor = new Sensor($this->connectDB->connectDB());
    }

    //----------------------------------------------------------------
    //ฟังก์ชันสำหรับการส่งค่ากลับไปยัง client (response to client)
    //----------------------------------------------------------------
    //ฟังก์ชันส่งผลกลับไปยัง client ใช้ในกรณีที่การส่งค่ากลับมีมากกว่า 1 รายการ/แถว/เรคอร์ด/ข้อมูล
    private function sendResponseFromResult($result)
    {
        if ($result) {
            $num = $result->rowCount();
            if ($num > 0) {
                $result_arr = array("message" => "ok", "data" => array());
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    $result_arr["data"][] = $row;
                }
                Response::sendResponse(200, $result_arr);
            } else {
                Response::sendResponse(404, ["message" => "No data found"]);
            }
        } else {
            Response::sendResponse(500, ["message" => "An error occurred while processing the request"]);
        }
    }

    //ฟังก์ชันส่งผลกลับไปยัง client ใช้ในกรณีที่การส่งค่ากลับมี 1 รายการ/แถว/เรคอร์ด/ข้อมูล
    private function sendResponseOneFromResult($result)
    {
        if ($result) {
            $num = $result->rowCount();
            if ($num > 0) {
                $row = $result->fetch(PDO::FETCH_ASSOC);
                Response::sendResponse(200, ["message" => "ok", "data" => $row]);
            } else {
                Response::sendResponse(404, ["message" => "No data found"]);
            }
        } else {
            Response::sendResponse(500, ["message" => "An error occurred while processing the request"]);
        }
    }

    //ฟังก์ชั่นส่งผลกลับไปยัง client ใช้ในกรณีการเพิ่มข้อมูล
    private function sendResponseCreateFromResult($result)
    {
        if ($result) {
            Response::sendResponse(201, ["message" => "ok"]);
        } else {
            Response::sendResponse(500, ["message" => "An error occurred while processing the request"]);
        }
    }

    //ฟังก์ชั่นส่งผลกลับไปยัง client ใช้ในกรณีการแก้ไขและลบข้อมูล
    private function sendResponseUpdateDeleteFromResult($result)
    {
        if ($result) {
            Response::sendResponse(201, ["message" => "ok"]);
        } else {
            Response::sendResponse(500, ["message" => "An error occurred while processing the request"]);
        }
    }

    //----------------------------------------------------------------
    //ฟังก์ชันการทำงานที่สอดรับกับการเรียกใช้ API (request จาก client) ที่ทำงานกับตารางในฐานข้อมูล
    //(C (create/เพิ่ม), R (read/ค้นหา ตรวจสอบ ดึง ดู), U (update/แก้ไข), D (delete/ลบ))
    //----------------------------------------------------------------
    //ฟังก์ชันดึงข้อมูลทั้งหมดจากตาราง sensor_tb และส่งผลการทำงานกลับไปยัง client
    public function getSensorAll()
    {
        $result = $this->sensor->getSensorAll();
        $this->sendResponseFromResult($result);
    }

    //ฟังก์ชันดึงข้อมูลทั้งหมดจากตาราง sensor_tb โดยมีเงื่อนไขตามวัน ที่ client ส่งมา และส่งผลการทำงานกลับไปยัง client
    public function getSensorAllByDate($date)
    {
        $result = $this->sensor->getSensorAllByDate($date);
        $this->sendResponseFromResult($result);
    }

    //ฟังก์ชันดึงข้อมูลทั้งหมดจากตาราง sensor_tb โดยมีเงื่อนไขตามวันและช่วงเวลา ที่ client ส่งมา และส่งผลการทำงานกลับไปยัง client
    public function getSensorAllByDateAndBetweenTime($date, $start_time, $end_time)
    {
        $result = $this->sensor->getSensorAllByDateAndBetweenTime($date, $start_time, $end_time);
        $this->sendResponseFromResult($result);
    }

    //ฟังก์ชันดึงข้อมูลทั้งหมดจากตาราง sensor_tb โดยมีเงื่อนไขตามช่วงวัน ที่ client ส่งมา และส่งผลการทำงานกลับไปยัง client
    public function getSensorAllByBetweenDate($start_date, $end_date)
    {
        $result = $this->sensor->getSensorAllByBetweenDate($start_date, $end_date);
        $this->sendResponseFromResult($result);
    }

    //ฟังก์ชันดึงข้อมูลจากตาราง sensor_tb เฉพาะค่า temperature อย่างเดียว โดยมีเงื่อนไขตามวัน ที่ client ส่งมา และส่งผลการทำงานกลับไปยัง client
    public function getSensorTempByDate($date)
    {
        $result = $this->sensor->getSensorTempByDate($date);
        $this->sendResponseFromResult($result);
    }

    //ฟังก์ชันบันทึกข้อมูลลงตาราง sensor_tb และส่งผลการทำงานกลับไปยัง client
    public function insertSensor($temperature, $humidity, $light, $pm_value, $recorded_date, $recorded_time)
    {
        $result = $this->sensor->insertSensor($temperature, $humidity, $light, $pm_value, $recorded_date, $recorded_time);
        $this->sendResponseCreateFromResult($result);
    }
}
