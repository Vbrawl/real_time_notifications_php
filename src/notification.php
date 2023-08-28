<?php


namespace REAL_TIME_NOTIFICATIONS {

    class Notification {

        private $notification_id = 0;
        private $receiver_id = 0;
        private $message = 0;
        private $db = 0;

        function __construct($db, $receiver_id, $message, $notification_id = 0) {
            $this->db = $db;
            $this->receiver_id = $receiver_id;
            $this->message = $message;
            $this->notification_id = $notification_id;
        }

        function add() {
            $id = $this->db->add_notification($this->receiver_id, $this->message);

            if($id != null) {
                $this->notification_id = $id;
            }
            return $id;
        }

        function delete() {
            return $this->db->delete_notification($this->receiver_id, $this->notification_id);
        }

        function get_notification_id() {
            return $this->notification_id;
        }

        function get_receiver_id() {
            return $this->receiver_id;
        }

        function get_message() {
            return $this->message;
        }

        function get_db() {
            return $this->db;
        }

    }

}
