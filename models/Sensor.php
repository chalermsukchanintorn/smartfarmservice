<?php
//----------------------------------------------------------------
//ไฟล์สำหรับการทำงานกับตารางในฐานข้อมูลที่จะทำงานด้วย โดยประกอบด้วยฟังก์ชันการทำงานต่างๆ กับตาราง (CRUD)
//ณ ไฟล์นี้คือทำงานกับตาราง sensor_tb
//----------------------------------------------------------------

class Sensor
{
    private $connDB; //ตัวแปรเก็บการเชื่อมต่อไปยังฐานข้อมูล
    private $table = "sensor_tb"; //ตัวแปรเก็บชื่อตารางที่จะทำงานด้วย

    //ตัวแปรที่ตรงกับชื่อคอลัมภ์ของตารางที่จะทำงานด้วย
    public $id;
    public $temperature;
    public $humidity;
    public $light;
    public $pm_value;
    public $recorded_date;
    public $recorded_time;

    //คอนสตรักเตอร์ที่จะกำหนดค่าการเชื่ิอมต่อฐานข้อมูลให้กับตัวแปร $connDB
    public function __construct($db)
    {
        $this->connDB = $db;
    }

    //----------------------------------------------------------------
    //ฟังก์ชันการทำงานกับตารางในฐานข้อมูล 
    //(C (create/เพิ่ม), R (read/ค้นหา ตรวจสอบ ดึง ดู), U (update/แก้ไข), D (delete/ลบ))
    //----------------------------------------------------------------
    //ฟังก์ชันดึงข้อมูลทั้งหมดจากตาราง sensor_tb 
    public function getSensorAll()
    {
        try {
            $query = "SELECT * FROM  $this->table ORDER BY recorded_date DESC";

            $stmt = $this->connDB->prepare($query);

            $stmt->execute();

            return $stmt;
        } catch (PDOException $e) {
            echo "Database Error in getSensorTempByDate: " . $e->getMessage();
            return false;
        } catch (Exception $e) {
            echo "General Error in getSensorTempByDate: " . $e->getMessage();
            return false;
        }
    }

    //ฟังก์ชันดึงข้อมูลทั้งหมดจากตาราง sensor_tb โดยมีเงื่อนไขตามวัน ที่ client ส่งมา
    public function getSensorAllByDate($date)
    {
        try {
            $query = "SELECT * FROM  $this->table  WHERE recorded_date= :recorded_date";

            $stmt = $this->connDB->prepare($query);

            $date = htmlspecialchars(strip_tags($date));

            $stmt->bindParam(":recorded_date", $date);

            $stmt->execute();

            return $stmt;
        } catch (PDOException $e) {
            echo "Database Error in getSensorTempByDate: " . $e->getMessage();
            return false;
        } catch (Exception $e) {
            echo "General Error in getSensorTempByDate: " . $e->getMessage();
            return false;
        }
    }

    //ฟังก์ชันดึงข้อมูลทั้งหมดจากตาราง sensor_tb โดยมีเงื่อนไขตามวันและช่วงเวลา ที่ client ส่งมา
    public function getSensorAllByDateAndBetweenTime($date, $start_time, $end_time)
    {
        try {
            $query = "SELECT * FROM $this->table WHERE recorded_date =  :recorded_date AND recorded_time BETWEEN :start_time AND :end_time";

            $stmt = $this->connDB->prepare($query);

            $date = htmlspecialchars(strip_tags($date));
            $start_time = htmlspecialchars(strip_tags($start_time));
            $end_time = htmlspecialchars(strip_tags($end_time));

            $stmt->bindParam(":recorded_date", $date);
            $stmt->bindParam(":start_time", $start_time);
            $stmt->bindParam(":end_time", $end_time);

            $stmt->execute();

            return $stmt;
        } catch (PDOException $e) {
            echo "Database Error in getSensorTempByDate: " . $e->getMessage();
            return false;
        } catch (Exception $e) {
            echo "General Error in getSensorTempByDate: " . $e->getMessage();
            return false;
        }
    }

    //ฟังก์ชันดึงข้อมูลทั้งหมดจากตาราง sensor_tb โดยมีเงื่อนไขตามช่วงวัน ที่ client ส่งมา
    public function getSensorAllByBetweenDate($start_date, $end_date)
    {
        try {
            $query = "SELECT * FROM $this->table WHERE recorded_date BETWEEN :stat_date AND :end_date";

            $stmt = $this->connDB->prepare($query);

            $start_date = htmlspecialchars(strip_tags($start_date));
            $end_date = htmlspecialchars(strip_tags($end_date));

            $stmt->bindParam(":stat_date", $start_date);
            $stmt->bindParam(":end_date", $end_date);

            $stmt->execute();

            return $stmt;
        } catch (PDOException $e) {
            echo "Database Error in getSensorTempByDate: " . $e->getMessage();
            return false;
        } catch (Exception $e) {
            echo "General Error in getSensorTempByDate: " . $e->getMessage();
            return false;
        }
    }

    //ฟังก์ชันดึงข้อมูลจากตาราง sensor_tb เฉพาะค่า temperature อย่างเดียว โดยมีเงื่อนไขตามวัน ที่ client ส่งมา
    public function getSensorTempByDate($date)
    {
        try {
            $query = "SELECT temperature FROM $this->table WHERE recorded_date = :recorded_date";

            $stmt = $this->connDB->prepare($query);

            $date = htmlspecialchars(strip_tags($date));

            $stmt->bindParam(":recorded_date", $date);

            $stmt->execute();

            return $stmt;
        } catch (PDOException $e) {
            echo "Database Error in getSensorTempByDate: " . $e->getMessage();
            return false;
        } catch (Exception $e) {
            echo "General Error in getSensorTempByDate: " . $e->getMessage();
            return false;
        }
    }

    //ฟังก์ชันบันทึกข้อมูลลงตาราง sensor_tb
    public function insertSensor($temperature, $humidity, $light, $pm_value, $recorded_date, $recorded_time)
    {
        try {
            $query = "INSERT INTO $this->table 
                        (temperature, humidity, light, pm_value, recorded_date, recorded_time) 
                    VALUES 
                        (:temperature, :humidity, :light, :pm_value, :recorded_date, :recorded_time)";

            $stmt = $this->connDB->prepare($query);

            $temperature = htmlspecialchars(strip_tags($temperature));
            $humidity = htmlspecialchars(strip_tags($humidity));
            $light = htmlspecialchars(strip_tags($light));
            $pm_value = htmlspecialchars(strip_tags($pm_value));
            $recorded_date = htmlspecialchars(strip_tags($recorded_date));
            $recorded_time = htmlspecialchars(strip_tags($recorded_time));

            $stmt->bindParam(':temperature', $temperature);
            $stmt->bindParam(':humidity', $humidity);
            $stmt->bindParam(':light', $light);
            $stmt->bindParam(':pm_value', $pm_value);
            $stmt->bindParam(':recorded_date', $recorded_date);
            $stmt->bindParam(':recorded_time', $recorded_time);

            $stmt->execute();

            return $stmt;
        } catch (PDOException $e) {
            echo "Database Error in getSensorTempByDate: " . $e->getMessage();
            return false;
        } catch (Exception $e) {
            echo "General Error in getSensorTempByDate: " . $e->getMessage();
            return false;
        }
    }
}
