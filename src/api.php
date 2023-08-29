<?php



namespace REAL_TIME_NOTIFICATIONS_API {

    function add_notification($db, $receiver_id, $message) {
        $notId = $db->add_notification($receiver_id, $message);

        if($notId == null) {
            return array(
                'status' => false
            );
        }
        else {
            return array(
                'status' => true,
                'notification_id' => $notId
            );
        }
    }

    function delete_notification($db, $receiver_id, $notification_id) {
        $status = $db->delete_notification($receiver_id, $notification_id);

        return array(
            'status' => $status
        );
    }

    function get_notifications($db, $receiver_id, $last_notification_id = 0) {
        $results = $db->get_notifications($receiver_id, $last_notification_id);

        $notifications = array();
        while($result = $results->getNotification()) {
            array_push($notifications, $result);
        }

        return $notifications;
    }



    function ExecuteAPI($db) {
        $results = array('status' => false);
        switch($_SERVER["REQUEST_METHOD"]) {
        case 'POST':
            $data = json_decode(file_get_contents("php://input"), true);
            if(!isset($data['receiver_id']) || filter_var($data['receiver_id'], FILTER_VALIDATE_INT) === false) {
                http_response_code(400);
                die('receiver_id must be specified and must be integer.');
            }
            else {
                $receiver_id = intval($data['receiver_id']);
            }

            if(!isset($data['message'])) {
                http_response_code(400);
                die('message must be specified.');
            }
            else {
                $message = $data['message'];
            }

            $results = add_notification($db, $receiver_id, $message);
            break;
        
        case 'DELETE':
            $data = json_decode(file_get_contents("php://input"), true);
            if(!isset($data['receiver_id']) || filter_var($data['receiver_id'], FILTER_VALIDATE_INT) === false) {
                http_response_code(400);
                die('receiver_id must be specified and must be integer.');
            }
            else {
                $receiver_id = intval($data['receiver_id']);
            }

            if(!isset($data['notification_id']) || filter_var($data['notification_id'], FILTER_VALIDATE_INT) === false) {
                http_response_code(400);
                die('notification_id must be specified and must be integer.');
            }
            else {
                $notification_id = intval($data['notification_id']);
            }

            $results = delete_notification($db, $receiver_id, $notification_id);
            break;

        case 'GET':
            if(!isset($_GET['receiver_id']) || filter_var($_GET['receiver_id'], FILTER_VALIDATE_INT) === false) {
                http_response_code(400);
                die('receiver_id must be specified and must be integer.');
            }
            else {
                $receiver_id = intval($_GET['receiver_id']);
            }

            $last_notification_id = 0;
            if(isset($_GET['last_notification_id'])) {
                if(filter_var($_GET['last_notification_id'], FILTER_VALIDATE_INT) === false) {
                    http_response_code(400);
                    die('last_notification_id, if specified, must be an integer.');
                }
                else {
                    $last_notification_id = intval($_GET['last_notification_id']);
                }
            }

            $results = get_notifications($db, $receiver_id, $last_notification_id);
            break;
        }

        echo json_encode($results);
    }
}