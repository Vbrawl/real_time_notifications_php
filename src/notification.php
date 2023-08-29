<?php


namespace REAL_TIME_NOTIFICATIONS {

    class Notification implements \JsonSerializable {

        private ?int $notification_id = null;
        private int $receiver_id = 0;
        private string $message = '';
        private ?Database $db = null;

        function __construct(Database &$db, int $receiver_id, string $message, ?int $notification_id = null) {
            $this->db = $db;
            $this->receiver_id = $receiver_id;
            $this->message = $message;
            $this->notification_id = $notification_id;
        }

        function add(): ?int {
            $id = $this->db->add_notification($this->receiver_id, $this->message);

            if($id != null) {
                $this->notification_id = $id;
            }
            return $id;
        }

        function jsonSerialize(): mixed {
            return array(
                'receiver_id' => $this->receiver_id,
                'message' => $this->message,
                'notification_id' => $this->notification_id
            );
        }

        function delete() : bool {
            return $this->db->delete_notification($this->receiver_id, $this->notification_id);
        }

        function get_notification_id() : int {
            return $this->notification_id;
        }

        function get_receiver_id() : int {
            return $this->receiver_id;
        }

        function get_message() : string {
            return $this->message;
        }

        function &get_db() : ?Database {
            return $this->db;
        }

    }

}
