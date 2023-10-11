<?php
	namespace App\Http\Traits;

use App\Models\Setting;

	trait SendNotification{
          
		function notifyByFirebase($title,$body,$tokens,$data = [])        // paramete 5 =>>>> $type
             {
                 $registrationIDs = $tokens;             
             // 'vibrate' available in GCM, but not in FCM
                 $fcmMsg = array(
                     'body' => $body,
                     'title' => $title,
                     'sound' => "default",
                     'color' => "#203E78"
                 );
                 $fcmFields = array(
                     'registration_ids' => $registrationIDs,
                     'priority' => 'high',
                     'notification' => $fcmMsg,
                     'data' => $data
                 );
            $firebase_key=Setting::where('key','firebase_access_key')->first()?->value['en'];

                 $headers = array(
                      'Authorization: key='.$firebase_key,
                      'Content-Type: application/json'
                  );
             
                 $ch = curl_init();
                 curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
                 curl_setopt($ch, CURLOPT_POST, true);
                 curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                 curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                 curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmFields));
                 $result = curl_exec($ch);
                 curl_close($ch);
                 return $result;
             }
             
             
	}
