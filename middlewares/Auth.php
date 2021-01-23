<?php
require __DIR__.'/../classes/JwtHandler.php';
class Auth extends JwtHandler{

    protected $database;
    protected $headers;
    protected $token;
    public function __construct($database,$headers) {
        parent::__construct();
        $this->database = $database;
        $this->headers = $headers;
    }

    public function isAuth(){
        if(array_key_exists('Authorization',$this->headers) && !empty(trim($this->headers['Authorization']))):
            $this->token = explode(" ", trim($this->headers['Authorization']));
            if(isset($this->token[1]) && !empty(trim($this->token[1]))):
                
                $data = $this->_jwt_decode_data($this->token[1]);

                if(isset($data['auth']) && isset($data['data']->user_id) && $data['auth']):
                    $user = $this->fetchUser($data['data']->user_id);
                    return $user;

                else:
                    return null;

                endif; 
                
            else:
                return null;

            endif;

        else:
            return null;

        endif;
    }

    protected function fetchUser($user_id){
        try{
            $fetch_user_by_id = "SELECT `firstname`,`lastname`,`email` FROM `users` WHERE `id`=:id";
            $query_statement = $this->database->prepare($fetch_user_by_id);
            $query_statement->bindValue(':id', $user_id,PDO::PARAM_INT);
            $query_statement->execute();

            if($query_statement->rowCount()):
                $row = $query_statement->fetch(PDO::FETCH_ASSOC);
                return [
                    'success' => 1,
                    'status' => 200,
                    'user' => $row
                ];
            else:
                return null;
            endif;
        }
        catch(PDOException $e){
            return null;
        }
    }
}
