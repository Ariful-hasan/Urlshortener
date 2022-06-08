<?php

namespace App\Utilities;

use GuzzleHttp\Client as GuzzleClient;


class UrlValidationService {

    private string $apiKey;
    private string $clientId;
    private string $clientVersion;
    private string $apiUrl;

    public function __construct()
    {
        $this->apiKey           = config('constants.SAFE_BRWOSING_API_KEY');
        $this->clientId         = config('constants.SAFE_BRWOSING_CLIENT_ID');
        $this->clientVersion    = config('constants.SAFE_BRWOSING_CLIENT_ID');
        $this->apiUrl           = config('constants.SAFE_BRWOSING_API_URL');
    }
    
       
    /**
     * isSafeUrl
     *
     * @param  mixed $url
     * @return bool
     */
    public function isSafeUrl(string $url): bool
    {
        try {
            $apiUrl = $this->apiUrl . $this->apiKey;
            
            $requestBody = [
                "client" => [
                  "clientId"        => $this->clientId,
                  "clientVersion"   => $this->clientVersion
                ],
                "threatInfo" => [
                  "threatTypes"     => ["MALWARE", "SOCIAL_ENGINEERING"],
                  "platformTypes"   => ["WINDOWS"],
                  "threatEntryTypes"=> ["URL"],
                  "threatEntries" => [
                    ["url" => $url]
                  ]
                ]
            ];

            $guzzle = new GuzzleClient();
            $response = $guzzle->request('POST', $apiUrl, $requestBody);
            
            if ($response->getStatusCode() == 200) {
                return true;
            }

            return false;  
            
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}