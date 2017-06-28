<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;

class SailthruChannel
{
    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     */
    public function send($user, Notification $notification)
    {
        $sailThruNotification = $notification->toSailthru($user);

        $apiKey = '###';
        $apiSecret = '###';
        $sailthruClient = new \Sailthru_Client($apiKey, $apiSecret);

        try {
            $response = $sailthruClient->send(
                $sailThruNotification->sailThruTemplate,
                $user->email,
                $sailThruNotification->sailThruParameters
            );
            if ( !isset($response['error']) ) {
                // everything OK
                return True;
                // do something here
            } else {
                // handle API error
                dd('Send Failed on API eror. ');
            }
        } catch (\Sailthru_Client_Exception $e) {

            // deal with exceptions
            dd(['Send Failed. ', $e->getMessage()]);
        }

        // Send notification to the $notifiable instance...
    }
}