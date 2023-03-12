<?php

    class dbh{

        private $servername;
        private $username;
        private $password;
        private $dbname;
        private $charset;

        public function connect()
        {
            $this->servername = "first-db-instance-3380.cwervarlznzc.us-east-2.rds.amazonaws.com";
            $this->username = "paul";
            $this->password = "uma2022";
            $this->dbname = "RamamurthyLibrary";
            $this->charset = "utf8mb4";

            try{
                $dsn = "mysql:host=".$this->servername.";dbname=".$this->dbname.";charset=".$this->charset; 
                $pdo = new PDO($dsn, $this->$username, $this->password); 
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                return $pdo;
            } catch(PDOException $e)
            {
                    echo "Connection failed: ".$e->getMessage();
            }
        }
    }
?>