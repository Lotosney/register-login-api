<?php
class Database{
    private $db_hosting_page = 'localhost';// can be whatever. Local host is default adress when you use Xampp
    private $db_name = 'users';// can be named as you like
    private $db_username = 'root';//default database administator in case you use Xampp
    private $db_password = '';// password, when you use xampp, then by efault there is no password

    public function databaseConnection(){
        //connection will be with PDO https://www.php.net/manual/en/book.pdo.php
        try{
            $connnnection = new PDO('mysql:host='.$this->db_host.';dbname='.$this->db_name,$this->db_username,$this->db_password);
            $connnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $connection;
        }
        catch(PDOException $e){
            echo "Connection error ".$e->getMessage(); 
            exit;
        }
          
    }
} 
