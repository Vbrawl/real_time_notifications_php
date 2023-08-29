<?php


namespace REAL_TIME_NOTIFICATIONS {

    class Results {
        private ?\DATABASE_ADAPTER\DBAdapter $results = null;
        private ?\DATABASE_ADAPTER\RESULTAdapter $db = null;

        function __construct(\DATABASE_ADAPTER\DBAdapter $db, \DATABASE_ADAPTER\RESULTAdapter $result) {
            $this->db = $db;
            $this->results = $result;
        }

        function getNotification() : ?Notification {
            $row = $this->results->getRowA();
            if($row) {
                return new Notification($this->db, $row["receiver_id"], $row['message'], $row['id']);
            }
        }
    }

}
