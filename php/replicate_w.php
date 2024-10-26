<?php

class ReplicateAPI {
  private $apiToken;

  public function __construct($replicate_api) {
    $this->apiToken = $replicate_api;
  }

  public function sendVoiceFile($voiceFileUrl) {
    $url = 'https://api.replicate.com/v1/predictions';

    $data = json_encode([
      'version' => 'cdd97b257f93cb89dede1c7584e3f3dfc969571b357dbcee08e793740bedd854',
      'input' => [
        'audio' => $voiceFileUrl
      ]
    ]);

    $curl = curl_init($url);

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, [
      "Authorization: Bearer {$this->apiToken}", 
      "Content-Type: application/json" 
    ]);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);  

    $response = curl_exec($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    curl_close($curl);

    if ($httpCode >= 200 && $httpCode < 300) {
      return json_decode($response, true); 
    } else {
      throw new Exception("API request failed with response: $response");
    }
  }
}

/*
$replicate_api = 'API_TOKEN';
$voiceFileUrl = 'https://replicate.delivery/mgxm/e5159b1b-508a-4be4-b892-e1eb47850bdc/OSR_uk_000_0050_8k.wav';

$api = new ReplicateAPI($replicate_api);

try {
  $result = $api->sendVoiceFile($voiceFileUrl);
  print_r($result);
} catch (Exception $e) {
  echo $e->getMessage();
}
 */
?>
