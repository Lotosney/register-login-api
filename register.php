<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

function message($success,$status,$message,$extra = []){
    return array_merge([
        'success' => $success,
        'status' => $status,
        'message' => $message
    ],$extra);
}
require __DIR__.'/classes/Database.php';
$db_connection = new Database();
$connection = $db_connection->databaseConnection();

$data = json_decode(file_get_contents("php://input"));
$returnData = [];

if($_SERVER["REQUEST_METHOD"] != "POST"):
    $returnData = message(0,404,'Strona nie znaleziona!');


elseif(!isset($data->firstname)
    || !isset($data->lastname) 
    || !isset($data->email) 
    || !isset($data->password)
    || empty(trim($data->firstname))
    || empty(trim($data->lastname))
    || empty(trim($data->email))
    || empty(trim($data->password))
    ):

    $fields = ['fields' => ['firstname','lastname','email','password']];
    $returnData = message(0,422,'Wypełnij wszystkie pola!',$fields);


else:
    $firstname = trim($data->firstname);
    $lastname = trim($data->lastname);
    $email = trim($data->email);
    $password = trim($data->password);
    $uppercase = preg_match('@[A-Z]@', $password);
    $lowercase = preg_match('@[a-z]@', $password);
    $number    = preg_match('@[0-9]@', $password);

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)):
        $returnData = message(0,422,'Zły Adres Email');
    
    elseif(!$uppercase || !$lowercase || !$number ||strlen($password) < 8):
        $returnData = message(0,422,'Hasło musi być w odpowiednim formacie');

    elseif(strlen($firstname) < 3):
        $returnData = message(0,422,'Imię musi mieć conajmniej 3 litery');    
    elseif(strlen($lastname) < 3):
        $returnData = message(0,422,'Nazwisko musi mieć conajmniej 3 litery ');

    else:
        try{

            $check_email = "SELECT `email` FROM `users` WHERE `email`=:email";
            $check_email_statement = $conn->prepare($check_email);
            $check_email_statement->bindValue(':email', $email,PDO::PARAM_STR);
            $check_email_statement->execute();

            if($check_email_statement->rowCount()):
                $returnData = message(0,422, 'Ten adres jest już w użyciu');
            
            else:
                $insert_query = "INSERT INTO `users`(`firstname`,`lastname`,`email`,`password`) VALUES(:firstname, :lastname, :email,:password)";

                $insert_statement = $conn->prepare($insert_query);

                // DATA BINDING
                $insert_statement->bindValue(':firstname', htmlspecialchars(strip_tags($firstname)),PDO::PARAM_STR);
                $insert_statement->bindValue(':lastname', htmlspecialchars(strip_tags($lastname)),PDO::PARAM_STR);
                $insert_statement->bindValue(':email', $email,PDO::PARAM_STR);
                $insert_statement->bindValue(':password', password_hash($password, PASSWORD_DEFAULT),PDO::PARAM_STR);

                $insert_statement->execute();

                $returnData = message(1,201,'Sukces.');

            endif;

        }
        catch(PDOException $e){
            $returnData = message(0,500,$e->getMessage());
        }
    endif;
    
endif;

echo json_encode($returnData);