<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common\models;
use yii\db\ActiveRecord;
use yii\helpers\StringHelper;
use yii\base\InvalidConfigException;

use Yii;

/**
 * Description of CustomActiveRecord
 *
 * @author avelare
 */
class CustomActiveRecord extends ActiveRecord {
    public static function getDb() {
        return Yii::$app->muhlbauer;
    }
    
    /**
     * Returns the schema information of the DB table associated with this AR class.
     * @return TableSchema the schema information of the DB table associated with this AR class.
     * @throws InvalidConfigException if the table for the AR class does not exist.
     */
    public static function getTableSchema()
    {
        $tableSchema = self::getDb()
            ->getSchema()
            ->getTableSchema(static::tableName());

        if ($tableSchema === null) {
            throw new InvalidConfigException('The table does not exist: ' . static::tableName());
        }

        return $tableSchema;
    }
}
