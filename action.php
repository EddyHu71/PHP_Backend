 <?php
 require_once "db.php";

class Crud extends Database
{
    public function fetchwhereorder($table,$where,$order){
        $sql = "SELECT * FROM ".$table." where ".$where." ORDER BY ".$order;
        $array = array();
        $query = mysqli_query($this->con,$sql);
        while($row = mysqli_fetch_assoc($query)){
            $array[] = $row;
        }
        return $array;
    }
    public function fetchorderlimit($table,$order,$limit){
        // return "abc";
        $sql = "SELECT * FROM ".$table." ORDER BY ".$order." LIMIT ".$limit;
        $array = array();
        $query = mysqli_query($this->con,$sql);
        while($row = mysqli_fetch_assoc($query)){
            $array[] = $row;
        }
        return $array;
    }
    public function fetchwhereorderlimit($table,$where,$order,$limit){
        $sql = "SELECT * FROM ".$table." where ".$where." ORDER BY ".$order." limit ".$limit;
        $array = array();
        $query = mysqli_query($this->con,$sql);
        while($row = mysqli_fetch_assoc($query)){
            $array[] = $row;
        }
        return $array;
    }
    public function fetchwhere($table,$where){
        $sql = "SELECT * FROM ".$table." where ".$where;
        $array = array();
        $query = mysqli_query($this->con,$sql);
        while($row = mysqli_fetch_assoc($query)){
            $array[] = $row;
        }
        return $array;
    }
    public function fetch($table){
        $sql = "SELECT * FROM ".$table;
        $array = array();
        $query = mysqli_query($this->con,$sql);
        while($row = mysqli_fetch_assoc($query)){
            $array[] = $row;
        }
        return $array;
    }
    public function insert($table,$fields){
        //"INSERT INTO table_name (, , ) VALUES ('m_name','qty')";
        $sql = "";
        $sql .= "INSERT INTO ".$table;
        $sql .= " (".implode(",", array_keys($fields)).") VALUES ";
        $sql .= "('".implode("','", array_values($fields))."')";
        $query = mysqli_query($this->con,$sql);
        if($query){
            return true;
        }
    }
    public function insertreturnid($table,$fields){
        //"INSERT INTO table_name (, , ) VALUES ('m_name','qty')";
        $sql = "";
        $sql .= "INSERT INTO ".$table;
        $sql .= " (".implode(",", array_keys($fields)).") VALUES ";
        $sql .= "('".implode("','", array_values($fields))."')";
        $query = mysqli_query($this->con,$sql);
        if($query){
            return mysqli_insert_id($this->con);
        }
    }
    public function update($table,$where,$fields){
        $sql = "";
        $condition = "";
        foreach ($where as $key => $value) {
            // id = '5' AND m_name = 'something'
            $condition .= $key . "='" . $value . "' AND ";
        }
        $condition = substr($condition, 0, -5);
        foreach ($fields as $key => $value) {
            //UPDATE table SET m_name = '' , qty = '' WHERE id = '';
            $sql .= $key . "='".$value."', ";
        }
        $sql = substr($sql, 0,-2);
        $sql = "UPDATE ".$table." SET ".$sql." WHERE ".$condition;
        if(mysqli_query($this->con,$sql)){
            return true;
        }
    }
    public function delete($table,$where){
        $sql = "";
        $condition = "";
        foreach ($where as $key => $value) {
            $condition .= $key . "='" . $value . "' AND ";
        }
        $condition = substr($condition, 0, -5);
        $sql = "DELETE FROM ".$table." WHERE ".$condition;
        if(mysqli_query($this->con,$sql)){
            return true;
        }
    }
}
    ?>
