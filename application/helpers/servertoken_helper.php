<?php

function getToken()
{
    $data = "sadda89d893jkh**($&#*isdfhkjsdhf89334324";
    $token_number = hash('sha512', $data);
    return $token_number;
}

function min_redeem()
{
    $CI =& get_instance();

    $CI->db->select('min_redeem');
    $CI->db->from('tbl_admin');
    return $CI->db->get()->row()->min_redeem;
}

function push_notification_android($device_id, $message)
{
    //API URL of FCM
    $url = 'https://fcm.googleapis.com/fcm/send';

    /*api_key available in:
    Firebase Console -> Project Settings -> CLOUD MESSAGING -> Server key*/    $api_key = SERVER_KEY;

    $fields = array(
        'registration_ids' => (is_array($device_id))?$device_id:[$device_id],
        'data' => array(
                "message" => $message
        )
    );

    //header includes Content type and api key
    $headers = array(
        'Content-Type:application/json',
        'Authorization:key='.$api_key
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
    $result = curl_exec($ch);
    if ($result === false) {
        die('FCM Send Error: ' . curl_error($ch));
    }
    curl_close($ch);
    // echo $result;
    // exit;
    return $result;
}

function Send_SMS($MobileNo, $MSZ)
{
    // <editor-fold defaultstate="collapsed" desc="Send SMS">
    $msz = urlencode($MSZ);
    // $url = "http://www.makemysms.in/api/sendsms.php?username=AndroOTP&password=Sms@123&sender=ANDROP&mobile=$MobileNo&message=$msz&type=1&product=1";
    // $url = "http://sms53.hakimisolution.com/api/sendhttp.php?authkey=8707A7FvZhWH0QH5ee4bcf4P11&mobiles=$MobileNo&message=$msz&sender=TITANI&route=4&country=0";
    $url = "https://securesmpp.com/api/sendmessage.php?usr=tiktokrummy&apikey=CCBB75C3EFECEB0C17CB&sndr=TIKTOK&ph=".$MobileNo."&Template_ID=template_id&message=".$msz;

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HEADER, true);
    curl_setopt($curl, CURLOPT_COOKIEFILE, 'cookie.txt');
    curl_setopt($curl, CURLOPT_COOKIEJAR, 'cookie.txt');
    // curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:35.0) Gecko/20100101 Firefox/35.0');
    $strc = curl_exec($curl);
    SMS_Log($MobileNo, $url, $strc);
    return $strc;
    // </editor-fold>
}

function Send_OTP($MobileNo, $OTP)
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => 
   

     'https://www.fast2sms.com/dev/bulkV2?authorization=py9FKLRBjD7wXAaPdHTGZmSgiv4W6xczYhQt5l0I1fJ83bskNoWHIf5LhXdFxnmg6BoAjGT43YK01pte&variables_values='.$OTP.'&route=otp&numbers='.$MobileNo,

      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'GET',
      CURLOPT_HTTPHEADER => array(
        'Cookie: XSRF-TOKEN=eyJpdiI6IjFONUx3cHhIQjNZWGZGd0hSVzU2cnc9PSIsInZhbHVlIjoiY1dsVktORjl1bmI5VDVlUU03VlFNVVhzYTFYK1Q5SmpwZ1B0TFBJdkpCdzF5R3FoVGZreXBmWGNDUVhDT0ZtdDVheG8yNVJqNUdTZmlyWk9kZEpwSUE9PSIsIm1hYyI6IjVhODVkYWJhOTE1YTgzNGZiZDUzZTY0ZTgzYThhZjIyNzUxOTMwOWRkYjY4ZTFlYjU3ZDhkMDgxYmJmYjFiZGUifQ%3D%3D; j7P8vHZCkiaNVNCMhEy87PtQFwrmr05fvdd7RfPG=eyJpdiI6IjcyOGdMZTRQN21NRXlVUTcyakkrMHc9PSIsInZhbHVlIjoiK0wrYVVWZ29DT2d2TndiMis2K2dcL2xPbFlrXC9GRUVcL0hKWVRWYmpGNDdKd3FQaVlCNFwvWUJHVVBXamhhOGRJeTF5aFZkRExieUNyN2N1ek5QMzRrM2JyVFJ2YVdUMVk2XC9YZGJcL040R3lkYjUxbXlXMmwrb1RBdThOY3B5Y1I3ejR5dTF3TnZVbWlUUDJGVVYyeExUSVwvT0hCTnB2c0NSbXdYd0V5dzkrSnNcLzJRWGhyOWM2Qm1LMXNhSXhwc05EWVJZTW1yR3JYdkNnNmJVaHJoN3VSMUxJdzRWRG5LQlJubm5ROHF1a2VTdlNwYndZOWxUbzZZSlQyYm5kU04zU08wa3VORkZYdmQ4VFdWWmVLK0FkYlpqUzJiclJ2d3JXMFBtZ2tpUE1iT25Yb29zUFVpa1BjbUxIUE95U29nUmZ5Z3A0NEl6TjFWcjRUMjhWTUZ4UGh0dzl4Y2xjMEc3UGdmNU8rUk9KbzU5dEVaZk5zNklxNENSY201WVJJZEZFVFBlclNqbVNleG8zc0F6V3JDaElKTG1YZU5zM0hINlFcL2k1TDV2UmxOaGFQQmM2bDNabWtaRklXdzRTaTVTYXZUNjVrYWZTbTZYU0xGWjVGSmJ0VytHUkJza0RkWjBiV2t4NllPZ1RmR1dmNkFVTnpDdVhzSWRWaWdLcDFcL2IxTHI1QWV2Y3RRaFcwTU5keXpzUkZZeFl6Wk5WeG42WTR3WlprMVp0Qm42cDJTdz0iLCJtYWMiOiIwM2Q4YmQwMmE1NDBjMzUzNzVjN2Q3M2Y5ZWY2Mzc2MGJmYzIxNzEyMmRkNWJhMzIyYTI3NzA5OWZkMGVhNGVlIn0%3D; laravel_session=eyJpdiI6ImFWUUl6cTNxeWlSajNBUGVRdHowVGc9PSIsInZhbHVlIjoiaGlFenpQdXZnbGtoWGtoQzBBTmNScmhTYUQ2ZEtsYitGdHBMSzNsbWV3a0Y3SXhSWjZqanh2R3NOUnFaV1p1ZFVpWFl3MUZnWXExUG0yNkUwM3ZuN0E9PSIsIm1hYyI6ImNhYWViYjBmOWJiOWYxNDY5MzQxMTRlY2JiNTQwMWU4OGI4NGM1ZjI4ZWZkOWY3MDY4NzlkYzNiZGY0MDhjNjkifQ%3D%3D'
      ),
    ));
    
    $response = curl_exec($curl);
    curl_close($curl);
    return  $response;
}

function SMS_Log($mobile, $url, $response)
{
    // <editor-fold defaultstate="collapsed" desc="Upload to EMR">
    $ci = & get_instance();
    $data = [
        'mobile' => $mobile,
        'url' => $url,
        'response' => $response,
        'added_date' => date('Y-m-d H:i:s')
    ];
    $ci->db->insert('tbl_sms_log', $data);
    return $ci->db->last_query();
    // </editor-fold>
}

function upload_image($file, $path, $i = '')
{
    $ci = &get_instance();
    if ($i !== '') {
        $_FILES['file']['name'] = $file['name'][$i];
        $_FILES['file']['type'] = $file['type'][$i];
        $_FILES['file']['tmp_name'] = $file['tmp_name'][$i];
        $_FILES['file']['error'] = $file['error'][$i];
        $_FILES['file']['size'] = $file['size'][$i];
        $ext = pathinfo($file['name'][$i], PATHINFO_EXTENSION);
    } else {
        $_FILES['file']['name'] = $file['name'];
        $_FILES['file']['type'] = $file['type'];
        $_FILES['file']['tmp_name'] = $file['tmp_name'];
        $_FILES['file']['error'] = $file['error'];
        $_FILES['file']['size'] = $file['size'];
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    }
    $config['upload_path'] = $path;
    $config['allowed_types'] = '*';
    $file_name =  date("Ymd_Hi") . "_" . uniqid() . "." . $ext;
    $config['file_name'] = $file_name;
    $ci->load->library('upload', $config);
    $ci->upload->initialize($config);
    if ($ci->upload->do_upload('file')) {
        $ci->upload->data();
        return $file_name;
    }
}

function upload_apk($file, $path, $i = '')
{
    $ci = &get_instance();

    $_FILES['file']['name'] = $file['name'];
    $_FILES['file']['type'] = $file['type'];
    $_FILES['file']['tmp_name'] = $file['tmp_name'];
    $_FILES['file']['error'] = $file['error'];
    $_FILES['file']['size'] = $file['size'];
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);

    $config['upload_path'] = $path;
    $config['allowed_types'] = '*';
    $file_name =  "game." . $ext;
    unlink($file_name);
    $config['file_name'] = $file_name;
    $ci->load->library('upload', $config);
    $ci->upload->initialize($config);
    if ($ci->upload->do_upload('file')) {
        $ci->upload->data();
        return $file_name;
    } else {
        $error = $ci->upload->display_errors();
        print_r($error);
        exit;
    }
}

function word_to_digit($word)
{
    $warr = explode(';', $word);
    $result = '';
    foreach ($warr as $value) {
        switch(trim(strtolower($value))) {
            case 'zero':
                $result .= '0';
                break;
            case 'one':
                $result .= '1';
                break;
            case 'two':
                $result .= '2';
                break;
            case 'three':
                $result .= '3';
                break;
            case 'four':
                $result .= '4';
                break;
            case 'five':
                $result .= '5';
                break;
            case 'six':
                $result .= '6';
                break;
            case 'seven':
                $result .= '7';
                break;
            case 'eight':
                $result .= '8';
                break;
            case 'nine':
                $result .= '9';
                break;
        }
    }
    return $result;
}

function shuffle_assoc($my_array)
{
    $keys = array_keys($my_array);

    shuffle($keys);

    foreach ($keys as $key) {
        $new[$key] = $my_array[$key];
    }

    $my_array = $new;

    return $my_array;
}

function minus_from_wallets($user_id, $amount, $minus_wallet=0)
{
    $CI =& get_instance();

    $CI->db->select('winning_wallet,unutilized_wallet,bonus_wallet');
    $CI->db->from('tbl_users');
    $CI->db->where('id', $user_id);
    $Query = $CI->db->get();

    $wallet_row = $Query->row();

    $unutilized_wallet = $wallet_row->unutilized_wallet;
    $unutilized_wallet_minus = ($unutilized_wallet>$amount) ? $amount : $unutilized_wallet;
    $amount -=$unutilized_wallet_minus;
    if ($unutilized_wallet_minus>0) {
        $CI->db->set('unutilized_wallet', 'unutilized_wallet-' . $unutilized_wallet_minus, false);
        if($minus_wallet==1) {
            $CI->db->set('wallet', 'wallet-' . $unutilized_wallet_minus, false);
        }
        $CI->db->where('id', $user_id);
        $CI->db->update('tbl_users');
    }
    if($amount>0) {
        $winning_wallet = $wallet_row->winning_wallet;
        $winning_wallet_minus = ($winning_wallet>$amount) ? $amount : $winning_wallet;
        $amount -=$winning_wallet_minus;
        if ($winning_wallet_minus>0) {
            $CI->db->set('winning_wallet', 'winning_wallet-' . $winning_wallet_minus, false);
            if($minus_wallet==1) {
                $CI->db->set('wallet', 'wallet-' . $winning_wallet_minus, false);
            }
            $CI->db->where('id', $user_id);
            $CI->db->update('tbl_users');
        }
    }

    if($amount>0) {
        $bonus_wallet = $wallet_row->bonus_wallet;
        $bonus_wallet_minus = ($bonus_wallet>$amount) ? $amount : $bonus_wallet;
        $amount -=$bonus_wallet_minus;
        if ($bonus_wallet_minus>0) {
            $CI->db->set('bonus_wallet', 'bonus_wallet-' . $bonus_wallet_minus, false);
            if($minus_wallet==1) {
                $CI->db->set('wallet', 'wallet-' . $bonus_wallet_minus, false);
            }
            $CI->db->where('id', $user_id);
            $CI->db->update('tbl_users');
        }
    }
    return true;
}