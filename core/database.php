<?php

class MYSQLDatabase
{
    private $connection;
    function __construct()
    {
        $this->open_connection();
    }
    public function open_connection()
    {
        $this->connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if (mysqli_connect_errno()) {
            die("Database Connection Failed" . mysqli_connect_error() . "(" . mysqli_connect_errno() . ")");
        }
    }
    public function close_connection()
    {
        if (isset($this->connection)) {
            mysqli_close($this->connection);
            unset($this->connection);
        }
    }
    public function query($sql)
    {
        $result = mysqli_query($this->connection, $sql);
        $this->confirm_query($result);
        return $result;
    }
    private function confirm_query($result)
    {
        if (!$result) {
            die("Database Query Failed " . mysqli_error($this->connection) . "( " . mysqli_errno($this->connection) . " )");
        }
    }
    public function free($result_set)
    {
        return mysqli_free_result($result_set);
    }

    public function affected()
    {
        return mysqli_affected_rows($this->connection);
    }

    public function escape($string)
    {
        $escaped_string = mysqli_real_escape_string($this->connection, $string);
        return $escaped_string;
    }

    public function assoc($result_set)
    {
        return mysqli_fetch_assoc($result_set);
    }

    public function result_assoc($sql)
    {
        $output = [];
        $query = $this->query($sql);
        while ($query2 = $this->assoc($query)) {
            $output[] = $query2;
        }
        return $output;
    }

    public function count($table, $where = "")
    {
        $result = $this->query("SELECT count(1) FROM $table " . (empty($where) ? "" : " WHERE $where"));
        return mysqli_fetch_array($result)[0];
    }

    public function insert($table, $data)
    {
        $values = array_values($data);
        foreach ($values as $key => $value) {
            if (empty($key)) {
                $values = [];
            }
            $values[] = mysqli_real_escape_string($this->connection, trim($value));
        }
        $result = $this->query("INSERT INTO `$table` (`" . implode("`,`", array_keys($data)) . "`) VALUES ('" . implode("','", $values) . "')");
        $this->confirm_query($result);
        return $this->last_id();
    }
    public function update($table, $data, $where = "")
    {
        $update = "";
        foreach ($data as $key => $value) {
            $update .= "`" . $key . "` = '" . mysqli_real_escape_string($this->connection, trim($value)) . "' , ";
        }
        $update = substr($update, 0, -3);
        if (empty($where)) {
            $result = $this->query("UPDATE `$table` SET $update");
        } else {
            $result = $this->query("UPDATE `$table` SET $update WHERE $where");
        }
        $this->confirm_query($result);
        return $this->affected();
    }
    public function select($table, $select = "*", $where = "", $order_by = "", $limit = "", $offset = "")
    {
        if (is_array($select)) {
            $select = "`" . implode("`,`", $select) . "`";
        }
        if (empty($where)) {
            $select =  "SELECT $select FROM `$table`";
        } else {
            $select =  "SELECT $select FROM `$table` WHERE $where";
        }
        if (!empty($order_by)) {
            $select .= " ORDER BY $order_by";
        }
        if (empty($offset) && !empty($limit)) {
            $select .= " LIMIT $limit";
        }
        if (!empty($offset)) {
            $select .= " LIMIT $offset,$limit";
        }
        return $this->result_assoc($select);
    }
    public function last_id()
    {
        return mysqli_insert_id($this->connection);
    }

    function __destruct()
    {
        $this->close_connection();
    }
}
