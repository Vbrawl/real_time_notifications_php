
(function(real_time_notifications, undefined) {

    real_time_notifications.APIFile = '';
    real_time_notifications.receiver_id = 0;
    real_time_notifications.last_notification_id = 0;
    real_time_notifications.onNewNotification = async (notification_object) => {};

    real_time_notifications.RealTimeNotification = class {
        constructor(receiver_id, message, notification_id) {
            this.receiver_id = receiver_id;
            this.message = message;
            this.notification_id = notification_id;
        }

        async add() {
            var response = await fetch(real_time_notifications.APIFile, {
                method: "POST",
                body: JSON.stringify({
                    receiver_id: this.receiver_id,
                    message: this.message
                })
            });

            var data = response.json();

            if(data.status) {
                return new real_time_notifications.RealTimeNotification(this.receiver_id, this.message, data.notification_id);
            }
        }

        async delete() {
            var response = await fetch(real_time_notifications.APIFile, {
                method: "DELETE",
                body: JSON.stringify({
                    receiver_id: this.receiver_id,
                    notification_id: this.notification_id
                })
            });

            var data = await response.json();

            return data.status;
        }
    }


    real_time_notifications.start_fetching = (interval) => {
        setInterval(async () => {
            var first_separator = '?';
            if(real_time_notifications.APIFile.includes(first_separator)) {
                first_separator = '&';
            }

            const response = await fetch(real_time_notifications.APIFile+`${first_separator}receiver_id=${real_time_notifications.receiver_id}&last_notification_id=${real_time_notifications.last_notification_id}`, {
                method: "GET"
            });

            const data = await response.json();
            if(data.length != 0)
                real_time_notifications.last_notification_id = data[data.length - 1].notification_id + 1;

            for (let i = 0; i < data.length; i++) {
                const notification_data = data[i];

                const notification = new real_time_notifications.RealTimeNotification(notification_data.receiver_id, notification_data.message, notification_data.notification_id);

                real_time_notifications.onNewNotification(notification);
            }
        }, interval);

        real_time_notifications.start_fetching = (interval) => {};
    }

}(window.real_time_notifications = window.real_time_notifications || {}))