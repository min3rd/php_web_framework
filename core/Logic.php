<?php

class Logic
{

    private DBManager $dbm;

    public function __construct()
    {
        $this->dbm = DBManager::getDBManager();
    }

    public function getDBManager()
    {
        return $this->dbm;
    }

    public function begin()
    {
        return $this->dbm->begin();
    }

    public function commit()
    {
        return $this->dbm->commit();
    }

    public function rollback()
    {
        return $this->dbm->rollback();
    }

    public function update(Model $model)
    {
        $dbm = $this->getDBManager();
        if (!$dbm) {
            return false;
        }
        if (!$model->update($dbm)) {
            return false;
        }
        return $model;
    }
}
