<?php

/**
 * @author vanminh.vu
 * @link https://wefree.club
 * @todo database table constructor
 */
class Model
{
    protected static $table_name = "";
    protected static $columns =  array();
    protected static $default_data = array();
    protected static $not_null_fields = array();
    protected static $auto_increasements = array();
    protected static $indexs = array();

    private $data = array();
    private $original_data = array();
    private $moddified_data = array();
    public static function parse($row)
    {
        foreach (static::$columns as $column) {
            if (!isset($row[$column])) {
                return false;
            }
        }
        $class_name = get_called_class();
        $model = new $class_name();
        $model->setDataRow($row);
        $model->setOriginalData($row);
        $model->initModdifiedData();
        return $model;
    }
    public static function insert(DBManager $dbm, $values = array())
    {
        if (!strtolower(static::$table_name)) {
            return false;
        }
        if (!$values) {
            return false;
        }
        if (!is_array($values)) {
            return false;
        }
        if (count($values) <= 0) {
            return false;
        }
        if (is_array(static::$not_null_fields) && count(static::$not_null_fields) > 0) {
            foreach (static::$not_null_fields as $column) {
                if (!isset($values[$column])) {
                    return false;
                }
            }
        }
        $safe_columns = array();
        $safe_data = array();
        $key_data = array();
        foreach (static::$columns as $column) {
            $safe_columns[] = "`$column`";
            if (isset($values[$column])) {
                $safe_data[$column] = "'" . str_replace("'", "\"", $values[$column]) . "'";
                $key_data[$column] = $values[$column];
            } else if (isset($auto_increasements[$column])) {
                $safe_data[$column] = "'DEFAULT'";
            } else if (isset(static::$default_data[$column])) {
                $safe_data[$column] = "'" . str_replace("'", "\"", static::$default_data[$column]) . "'";
            } else {
                $safe_data[$column] = "''";
            }
        }
        if (count($safe_columns) <= 0 || count($safe_data) <= 0 || count($safe_columns) != count($safe_data)) {
            return false;
        }
        $insert_sql = "INSERT INTO " . strtolower(static::$table_name)
            . " (" . implode(",", $safe_columns) . ") VALUES (" . implode(",", $safe_data) . ");";
        $result = $dbm->query($insert_sql);
        if (!$result) {
            return false;
        }
        return self::find($dbm, $key_data);
    }

    /**
     * @param DBManager $dbm
     * @param array $search_values
     * @param string $oder {order by...}
     * @param string $limit
     */
    public static function find(DBManager $dbm, $search_values = array(), $order = false, $limit = false)
    {
        if (!$search_values) {
            return false;
        }
        if (!is_array($search_values)) {
            return false;
        }
        // if (count($search_values) <= 0) {
        //     return false;
        // }
        if (!$dbm) {
            return false;
        }
        $safe_condition = array();
        foreach ($search_values as $column => $value) {
            if (!in_array($column, static::$columns)) {
                continue;
            }
            $safe_condition[] = "`$column`='" . str_replace("'", "\"", $value) . "'";
        }
        $search_sql = "SELECT * FROM " . strtolower(static::$table_name);

        if (is_array($safe_condition) && count($safe_condition) > 0) {
            $search_sql .= " WHERE " . implode("AND", $safe_condition);
        }

        if ($order !== false) {
            $search_sql .= " $order ";
        }
        if ($limit !== false) {
            $search_sql .= " $limit ";
        }
        $result = $dbm->query($search_sql);
        if (!$result) {
            return false;
        }
        if ($result->num_rows <= 0) {
            return false;
        }
        $row = $result->fetch_assoc();
        return static::parse($row);
    }

    public static function findCustom(DBManager $dbm, $where = false, $order = false, $limit = false)
    {
        if (!$dbm) {
            return false;
        }

        $search_sql = "SELECT * FROM " . strtolower(static::$table_name);
        if ($where !== false) {
            $search_sql .= " $where ";
        }

        if ($order !== false) {
            $search_sql .= " $order ";
        }

        if ($limit !== false) {
            $search_sql .= " $limit ";
        }
        $result = $dbm->query($search_sql);
        if (!$result) {
            return false;
        }
        if ($result->num_rows <= 0) {
            return false;
        }
        $row = $result->fetch_assoc();
        return static::parse($row);
    }

    /**
     * @return Model[] $return_data
     */
    public static function findAll(DBManager $dbm, $search_values = array(), $order = false, $limit = false)
    {
        if (!is_array($search_values)) {
            return false;
        }
        if (!$dbm) {
            return false;
        }
        $safe_condition = array();
        foreach ($search_values as $column => $value) {
            if (!in_array($column, static::$columns)) {
                continue;
            }
            $safe_condition[] = "`$column`='" . str_replace("'", "\"", $value) . "'";
        }
        $search_sql = "SELECT * FROM " . strtolower(static::$table_name);

        if (is_array($safe_condition) && count($safe_condition) > 0) {
            $search_sql .= " WHERE " . implode("AND", $safe_condition);
        }

        if ($order !== false) {
            $search_sql .= " $order ";
        }
        if ($limit !== false) {
            $search_sql .= " $limit ";
        }
        $result = $dbm->query($search_sql);
        if (!$result) {
            return false;
        }
        if ($result->num_rows <= 0) {
            return false;
        }
        $return_data = array();
        while ($row = $result->fetch_assoc()) {
            $return_data[] = self::parse($row);
        }
        return $return_data;
    }

    public static function findAllCustom(DBManager $dbm, $where = false, $order = false, $limit = false)
    {
        if (!$dbm) {
            return false;
        }
        $search_sql = "SELECT * FROM " . strtolower(static::$table_name);
        if ($where !== false) {
            $search_sql .= " $where ";
        }

        if ($order !== false) {
            $search_sql .= " $order ";
        }

        if ($limit !== false) {
            $search_sql .= " $limit ";
        }
        $result = $dbm->query($search_sql);
        if (!$result) {
            return false;
        }
        if ($result->num_rows <= 0) {
            return false;
        }
        $return_data = array();
        while ($row = $result->fetch_assoc()) {
            $return_data[] = self::parse($row);
        }
        return $return_data;
    }

    public function update(DBManager $dbm)
    {
        if (count($this->moddified_data) < 0) {
            return false;
        }
        if (!$dbm) {
            return false;
        }

        $safe_condition = array();
        $safe_change_value = array();
        foreach (static::$columns as $column) {
            if (!isset($this->original_data[$column])) {
                Logger::error(get_called_class() . " original_data_fetch_failed", "Column=$column");
                return false;
            }
            $safe_condition[] = "`$column`='" . str_replace("'", "\"", $this->original_data[$column]) . "'";
            if ($this->moddified_data[$column]) {
                $safe_change_value[] = "`$column`='" . str_replace("'", "\"", $this->data[$column]) . "'";
            }
        }

        $updateSql = "UPDATE " . strtolower(static::$table_name);
        if (is_array($safe_change_value) && count($safe_change_value) > 0) {
            $updateSql .= " SET " . implode(",", $safe_change_value);
        }

        if (is_array($safe_condition) && count($safe_condition) > 0) {
            $updateSql .= " WHERE " . implode("AND", $safe_condition);
        }
        $result = $dbm->query($updateSql);
        if (!$result) {
            return false;
        }
        return $result;
    }

    public static function delete(DBManager $dbm, $search_values = array())
    {
        if (!is_array($search_values)) {
            return false;
        }
        if (!$dbm) {
            return false;
        }
        $safe_condition = array();
        foreach ($search_values as $column => $value) {
            if (!in_array($column, static::$columns)) {
                continue;
            }
            $safe_condition[] = "`$column`='" . str_replace("'", "\"", $value) . "'";
        }
        $deleteSql = "DELETE FROM " . strtolower(static::$table_name);
        if (is_array($safe_condition) && count($safe_condition) > 0) {
            $deleteSql .= " WHERE " . implode("AND", $safe_condition);
        }
        $result = $dbm->query($deleteSql);
        if (!$result) {
            return false;
        }
        return $result;
    }

    public static function count(DBManager $dbm, $search_values = array())
    {
        // if (!$search_values) {
        //     return false;
        // }
        if (!is_array($search_values)) {
            return false;
        }
        if (!$dbm) {
            return false;
        }
        $safe_condition = array();
        foreach ($search_values as $column => $value) {
            if (!in_array($column, static::$columns)) {
                continue;
            }
            $safe_condition[] = "`$column`='" . str_replace("'", "\"", $value) . "'";
        }
        $search_sql = "SELECT COUNT(*) FROM " . strtolower(static::$table_name);

        if (is_array($safe_condition) && count($safe_condition) > 0) {
            $search_sql .= " WHERE " . implode("AND", $safe_condition);
        }

        $result = $dbm->query($search_sql);
        if (!$result) {
            return false;
        }
        if ($result->num_rows <= 0) {
            return false;
        }
        $row = $result->fetch_assoc();
        return intval($row['COUNT(*)']);
    }
    public static function countCustom(DBManager $dbm, $where = false)
    {
        if (!$dbm) {
            return false;
        }
        $search_sql = "SELECT COUNT(*) FROM " . strtolower(static::$table_name);

        if ($where !== false) {
            $search_sql .= " $where ";
        }

        $result = $dbm->query($search_sql);
        if (!$result) {
            return false;
        }
        if ($result->num_rows <= 0) {
            return false;
        }
        $row = $result->fetch_assoc();
        return intval($row['COUNT(*)']);
    }

    public function getData($column)
    {
        if (!isset($this->data[$column])) {
            return false;
        }
        return $this->data[$column];
    }

    public function getDataInt($column)
    {
        return intval($this->getData($column));
    }

    public function getDataFloat($column)
    {
        return floatval($this->getData($column));
    }
    public function setDataRow($row)
    {
        if (!is_array($row)) {
            return false;
        }
        $this->data = $row;
    }
    public function setOriginalData($row)
    {
        if (!is_array($row)) {
            return false;
        }
        $this->original_data = $row;
    }
    public function initModdifiedData()
    {
        $this->moddified_data = array();
        foreach (static::$columns as $column) {
            $this->moddified_data[$column] = false;
        }
    }
    public function setData($column, $value)
    {
        if (!isset($this->data[$column])) {
            return false;
        }
        $this->data[$column] = $value;
        $this->moddified_data[$column] = true;
        return true;
    }

    public function setDataInt($column, $value)
    {
        return $this->setData($column, intval($value));
    }

    public function setDataFloat($column, $value)
    {
        return $this->setData($column, floatval($value));
    }
    public function checkBit($column, $bit)
    {
        $value = $this->getDataInt($column);
        return $value & $bit;
    }
    public function addBit($column, $bit)
    {
        $value = $this->getDataInt($column);
        $this->setDataInt($column, $value | $bit);
    }
    public function clearBit($column, $bit)
    {
        $value = $this->getDataInt($column);
        $this->setDataInt($column, $value & ~$bit);
    }
    public function getDataRow()
    {
        return $this->data;
    }
}
