# Download
```
$ phppm download -pu https://github.com/vbrawl/real_time_notifications_php
```

# Add To Project
```
$ phppm add -pn real_time_notifications_php
$ phppm resolve
```


# Usage

## Import
```
<?php
require_once('relocation.php');
require_once(REAL_TIME_NOTIFICATIONS_PATH.'/main.php');
```

## Use Example
```
require_once(DATABASE_ADAPTER_PATH.'/main.php');

$dbadapter = new DATABASE_ADAPTER\SQLITE3Database(':memory:');

$db = new REAL_TIME_NOTIFICATIONS\Database($dbadapter);

$db->init_db();

$notification = new Notification($db, 1, 'test notification');
$notification->add();

$notifications = $db->get_notifications(1);
$notification1 = $notifications->getNotification();
// $notification and $notification1 point the the same notification in the database, the objects are not linked though.

$notification1->delete();
```

## API Use Example
**Front-End PHP File**
```
<?php require_once('relocation.php'); ?>
<html>
    <head>
        <title>Test File</title>
        <script src="<?php echo REAL_TIME_NOTIFICATIONS_PATH.'/src/js/real_time_notifications.js'; ?>"></script>
        <script>
            window.real_time_notifications.APIFile = '/backendPHPfile.php';
            window.real_time_notifications.onNewNotification = (notification_obj) => {
                console.log(notification_obj);
            };
            window.real_time_notifications.receiver_id = 1;
            window.real_time_notifications.start_fetching(5000);
        </script>
    </head>
</html>
```

**Back-End PHP File**
```
<?php

require_once('relocation.php');
require_once(DATABASE_ADAPTER_PATH.'/main.php');
require_once(REAL_TIME_NOTIFICATIONS_PATH.'/main.php');
require_once(REAL_TIME_NOTIFICATIONS_PATH.'/src/api.php');

$dbadapter = new DATABASE_ADAPTER\SQLITE3Database('test.db');
$db = new REAL_TIME_NOTIFICATIONS\Database($dbadapter);
$db->init_db();

REAL_TIME_NOTIFICATIONS_API\ExecuteAPI($db);
```