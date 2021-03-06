<?php

namespace AppBundle\Services;


class FCMService
{
    const API_URL = 'https://fcm.googleapis.com/fcm/send';
    protected $fcm_server_key;

    /**
     * GCMService constructor.
     * @param string $fcm_server_key
     */
    public function __construct($fcm_server_key)
    {
        $this->fcm_server_key = $fcm_server_key;
    }

    /**
     * Send GCM Request
     * @param array $data
     * @param array $ids
     * @return array
     * */
    public function send(array $data, array $ids){
        if (!$this->_isCurl()) {
            return ['error' => 'cURL must be active in this system.'];
        }

        $apiKey = $this->fcm_server_key;
        $post = ['registration_ids' => $ids, 'data' => $data];
        $headers = ['Authorization: key=' . $apiKey, 'Content-Type: application/json'];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, FCMService::API_URL);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post));
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            return ['error' => curl_error($ch)];
        }

        curl_close($ch);
        return $result;
    }

    public function _isCurl() {
        return function_exists('curl_version');
    }
}