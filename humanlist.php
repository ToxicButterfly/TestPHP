<?php
/**
 * Второй Doc-block
 * Мною было непонято условие насчёт конструктора,
 * что же значит (поддержка выражений больше, меньше, не равно).
 * Я предположил, что нужно добавить при объявлении объекта ещё символ <,> или !=
 * и id, чтобы конструктор брал из базы данных только тех людей, чьи id будут больше,
 * меньше или не равны заданному, но это лишь реализованное предположение. 
 * 
 * По остальному комментарии не нужны, всё работает как должно - есть метод-фабрика создания объектов
 * людей класса Human, который добавляет все созданные объекты в массив и передаёт в метод удаления,
 * где каждый из созданных объектов класса Human удаляет себя из БД.
 * @author ToxicBfly
 */
class HumanList
{

    // database connection and table name
    private $conn;
    private $table_name = "firsttask";
    
    public $mas;

    public function __construct($db, $sym, $num)
    {
        $this->conn = $db;
        if ($sym === '<') {
            $query="SELECT
                    id
                    FROM
                    " . $this->table_name . "
                    WHERE id < " . $num;
        } else if($sym === '>') {
            $query="SELECT
                    id
                    FROM
                    " . $this->table_name . "
                    WHERE id > " . $num;
        } else if($sym === '!=') {
            $query="SELECT
                    id
                    FROM
                    " . $this->table_name . "
                    WHERE id != " . $num;
        } else {
            echo "Некорректный ввод";
            return;
        }
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $goted=$stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->mas = $goted;
        print_r($this->mas);
    }

    public function getMas()
    {
        if (class_exists('Human')) {
            $array = array();
            for($i = 0; $i < count($this->mas); $i++) {
                $obj = new Human($this->conn, "1", "2", "3", "4", "5", (int)$this->mas[$i]['id']);
                array_push($array, $obj);
            }
            return $array;
        } else {
            echo "Класс Human не существует";
            return;
        }
    }
    public function massDeleteHuman()
    {
        $array=$this->getMas();
        for($i=0; $i < count($array); $i++)
        {
            $array[$i]->delItem($array[$i]->id);
        }
    }
}
?>