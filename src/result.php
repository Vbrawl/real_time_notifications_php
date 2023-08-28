<?php


namespace REAL_TIME_NOTIFICATIONS {

    require_once(REAL_TIME_NOTIFICATIONS_PATH.'/src/notification.php');

    class Results {
        private $results = null;
        private $db = null;

        function __construct($db, $result) {
            $this->db = $db;
            $this->results = $result;
        }

        function getNotification() {
            $row = $this->results->getRowA();
            if($row) {
                $noti = new Notification($this->db, $row["receiver_id"], $row['message'], $row['id']);
                return $noti;
            }
        }
    }

}
