<?php
//----------------------------------------------------------------
//ไฟล์สำหรับการเชื่อมต่อไปยังฐานข้อมูลที่จะทำงานด้วย
//ณ ไฟล์นี้คือเชื่อมต่อไปยังฐานข้อมูล smart_fram_db
//----------------------------------------------------------------

class Database {
    //ตัวแปรเก็บรายละเอียดของฐานข้อมูลที่จะทำงานด้วย
    private $host = "mysql-176c9461-chanintornchalermsuk-cbcc.e.aivencloud.com:28758";
    private $dbname = "smart_farm_db";
    private $username = "avnadmin";
    private $password = "AVNS_iCVG_iD8oKPeiS2NKac";

    //ตัวแปรเก็บการเชื่อมต่อฐานข้อมูล
    private $connDB;

    public function connectDB() {
        $this->connDB = null;

        try {
            $this->connDB = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->dbname, $this->username, $this->password);
            $this->connDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Database Connection Error: " . $e->getMessage();
            return null;
        }
        
        return $this->connDB;
    }
}
?>
