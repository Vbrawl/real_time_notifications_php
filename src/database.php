<?php

namespace REAL_TIME_NOTIFICATIONS {

    class Database {

        private ?\DATABASE_ADAPTER\DBAdapter $dbadapter = null;

        public function __construct(\DATABASE_ADAPTER\DBAdapter $dbadapter) {
            $this->dbadapter = $dbadapter;
        }

        public function init_db() : void {
            if(!$this->dbadapter->isConnected()) $this->dbadapter->connect();
            $this->dbadapter->exec('CREATE TABLE IF NOT EXISTS `real_time_notifications` (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                receiver_id INTEGER,
                message TEXT,
                deleted INTEGER DEFAULT 0
            );'); // deleted is Boolean
        }

        public function connect() : void {
            $this->dbadapter->connect();
        }

        public function close() : void {
            $this->dbadapter->close();
        }

        public function add_notification(int $receiver_id, string $message) : ?Notification {
            if(!$this->dbadapter->isConnected()) $this->dbadapter->connect();
            $res = $this->dbadapter->execPrepared('INSERT INTO `real_time_notifications` (receiver_id, message) VALUES (:receiver_id, :message);', array(':receiver_id' => $receiver_id, ':message' => $message));

            if($res) {
                return $this->dbadapter->lastInsertRowId();
            }
        }

        public function get_notifications(int $receiver_id, int $minimum_id = 0) : ?Results {
            if(!$this->dbadapter->isConnected()) $this->dbadapter->connect();
            $res = $this->dbadapter->queryPrepared('SELECT id, receiver_id, message FROM `real_time_notifications` WHERE receiver_id = :receiver_id AND id >= :minimum_id AND deleted = 0 ORDER BY id;', array(':receiver_id' => $receiver_id, ':minimum_id' => $minimum_id));

            if($res != null) {
                return new Results($this, $res);
            }
        }

        public function delete_notification(int $receiver_id, int $notification_id) : bool {
            if(!$this->dbadapter->isConnected()) $this->dbadapter->connect();
            $res = $this->dbadapter->execPrepared('UPDATE `real_time_notifications` SET deleted=1 WHERE receiver_id = :receiver_id AND id = :notification_id AND deleted = 0;', array(':receiver_id' => $receiver_id, ':notification_id' => $notification_id));
            return $res;
        }
    }
}
