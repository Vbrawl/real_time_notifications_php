<?php

namespace REAL_TIME_NOTIFICATIONS {

    $DATABASE_TABLE_NAME = 'real_time_notifications';

    class Database {

        private $dbadapter = null;

        public function __construct($dbadapter) {
            $this->dbadapter = $dbadapter;
        }

        public function init_db() {
            global $DATABASE_TABLE_NAME;
            if(!$this->dbadapter->isConnected()) $this->dbadapter->connect();
            $this->dbadapter->exec('CREATE TABLE IF NOT EXISTS `'.$DATABASE_TABLE_NAME.'` (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                receiver_id INTEGER,
                message TEXT,
                deleted INTEGER DEFAULT 0
            );'); // deleted is Boolean
        }

        public function connect() {
            $this->dbadapter->connect();
        }

        public function close() {
            $this->dbadapter->close();
        }

        public function add_notification($receiver_id, $message) {
            global $DATABASE_TABLE_NAME;
            if(!$this->dbadapter->isConnected()) $this->dbadapter->connect();
            $res = $this->dbadapter->execPrepared('INSERT INTO `'.$DATABASE_TABLE_NAME.'` (receiver_id, message) VALUES (:receiver_id, :message);', array(':receiver_id' => $receiver_id, ':message' => $message));

            if($res) {
                return $this->dbadapter->lastInsertRowId();
            }
        }

        public function get_notifications($receiver_id, $minimum_id = 0) {
            global $DATABASE_TABLE_NAME;
            if(!$this->dbadapter->isConnected()) $this->dbadapter->connect();
            $res = $this->dbadapter->queryPrepared('SELECT id, receiver_id, message FROM `'.$DATABASE_TABLE_NAME.'` WHERE receiver_id = :receiver_id AND id >= :minimum_id AND deleted = 0 ORDER BY id;', array(':receiver_id' => $receiver_id, ':minimum_id' => $minimum_id));

            if($res != null) {
                return new Results($this, $res);
            }
        }

        public function delete_notification($receiver_id, $notification_id) {
            global $DATABASE_TABLE_NAME;
            if(!$this->dbadapter->isConnected()) $this->dbadapter->connect();
            $res = $this->dbadapter->execPrepared('UPDATE `'.$DATABASE_TABLE_NAME.'` SET deleted=1 WHERE receiver_id = :receiver_id AND id = :notification_id AND deleted = 0;', array(':receiver_id' => $receiver_id, ':notification_id' => $notification_id));
            return $res;
        }
    }
}
