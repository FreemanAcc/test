<?php
class DB
{
    protected static $dsn = "mysql:dbname=test_task;host=127.0.0.1";
    protected static $username = "root";
    protected static $password = "";

    public static $connect = null;

    public static function connect()
    {

        if (static::$connect == null)
            static::$connect =  new PDO(static::$dsn, static::$username, static::$password);
    }


    public static function all($table)
    {
        $stmt = self::$connect->prepare('SELECT * FROM `'.$table.'`');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



    public static function row($sql, $params = [])
    {

        self::connect();
        $sth = self::$connect->prepare($sql);

        if ( !empty($params) ) {
            foreach ($params as $key => $value) {
                $sth->bindValue(":$key", $value);
            }
        }

        $result = $sth->execute($params);

        if (false === $result) {
            return false;
        }

        $res = $sth->fetchAll();

        if (isset($res[0]))
            return $res[0];
        else
            return false;

    }

    public static function insert($table, $params){
        self::connect();
        $values = [];
        $keys = [];
        if ( !empty($params) ) {
            foreach ($params as $key => $value) {
                $values[] = ':'.$key;
                $keys[] = $key;
            }

        }
        $stmt = self::$connect->prepare("INSERT INTO ". $table ." (". implode(',',$keys).") VALUES (".implode(',',$values).")");

        if ( !empty($params) ) {
            foreach ($params as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }
        }


        $stmt->execute();
    }

    public static function update($table,  $params = null, $whereParams = null){
        self::connect();
        $setArr = [];
        $values = [];
        $whereArr = [];
        $whereValues = [];
        if (!empty($params) ) {
            foreach ($params as $key => $value){
                $setArr[] = $key.' = ? ';
                $values[] = $value;
            }

            $sql = "UPDATE `".$table."` SET ".implode(',',$setArr);
            if (!empty($whereParams) ) {

                foreach ($whereParams as $key => $value){
                    $whereArr[] = $key.' = ? ';
                    $whereValues[] = $value;
                }
                $sql .= " WHERE ". implode(',',$whereArr);
            }
            self::$connect->prepare($sql)->execute(array_merge($values,$whereValues));
        }

    }

}