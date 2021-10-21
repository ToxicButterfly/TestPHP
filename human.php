<?php

/**
 * Первый Doc-block
 * PHP не поддерживает перегрузку, поэтому
 * в конструктор пришлось добавить условие для двух разных случаев
 * и когда хочешь взять для объекта данные из БД по id, то надо
 * добавлять в вызов 5 ненужных аргументов. Реализация не блеск, но лучшая найденная.
 * @author ToxicBfly
 */

 class Human extends stdClass
{
    // database connection and table name
    private $conn;
    private $table_name = "firsttask";
  
    // object properties
    public $id;
    public $name;
    public $surname;
    public $birthday;
    public $sex;
    public $birthplace;

    public function __construct($db, $name, $surname, $birthday, $sex, $birthplace, $id)
    {
        $this->conn = $db;
        if(!$id)
        {
            $this->name=$name;
            if (!is_string($name)) {
                echo "Объект не был создан!1";
                return;
            }
            $this->surname=$surname;
            if (!is_string($surname)) {
                echo "Объект не был создан!2";
                return;
            }
            $this->birthday=$birthday;
            if (!is_string($birthday)) {
                echo "Объект не был создан!3";
                return;
            }
            $this->sex=$sex;
            if (!is_bool($sex)) {
                echo "Объект не был создан!4";
                return;
            }
            $this->birthplace=$birthplace;
            if (!is_string($birthplace)) {
                echo "Объект не был создан!5";
                return;
            }
            $this->create();
        } else {
            $query = "SELECT 
                        *
                    FROM
                        " . $this->table_name . "
                    WHERE id=:id";
            $stmt = $this->conn->prepare($query);  
            $stmt->bindParam(":id", $id);
            $stmt->execute();
            $smn = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $this->id = $smn[0]['id'];
            $this->name = $smn[0]['name'];
            $this->surname = $smn[0]['surname'];
            $this->birthday = $smn[0]['birthday'];
            $this->sex = $smn[0]['sex'];
            $this->birthplace = $smn[0]['birthplace'];
        }
    }

    function create()
    {
        $query = "INSERT INTO
        " . $this->table_name . "
        SET
        name=:name, surname=:surname, birthday=:birthday, sex=:sex, birthplace=:birthplace";
        $stmt = $this->conn->prepare($query);  

        // bind values
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":surname", $this->surname);
        $stmt->bindParam(":birthday", $this->birthday);
        $stmt->bindParam(":sex", $this->sex);
        $stmt->bindParam(":birthplace", $this->birthplace);

        if ($stmt->execute())
            return true;
        else
            return false;
    }

    function delItem($id)
    {
        $query= "DELETE FROM " . $this->table_name . " WHERE ID = $id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
    }

    static function getAge($birthday)
    {
        date_default_timezone_set('UTC');
        $date = date('Y-m-d');
        $age = (int)substr($date,0,4) - (int)substr($birthday,0,4);
        if ((int)substr($date,5,2) - (int)substr($birthday,5,2) > 0)
            $age--;
        else if ((int)substr($date,5,2) == (int)substr($birthday,5,2)) {
            if ((int)substr($date,8,2) - (int)substr($birthday,8,2) < 0)
                $age--;
        }
        return strval($age);
    }

    static function getSex($sex)
    {
        if ($sex)
            return 'Female';
        else
            return 'Male';
    }

    function changePerson()
    {
        $smn = new stdClass();
        $smn->id = $this->id;
        $smn->name = $this->name;
        $smn->surname = $this->surname;
        $smn->birthday = $this->getAge($this->birthday);
        $smn->sex = $this->getSex($this->sex);
        $smn->birthplace = $this->birthplace;
        return $smn;
    }
}
?>