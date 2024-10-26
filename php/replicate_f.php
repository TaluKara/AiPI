<?php

class ReplicateAPI
{
  private $apiToken;
  private $apiUrl = 'https://api.replicate.com/v1/models/black-forest-labs/flux-schnell/predictions';

  public function __construct($apiToken)
  {
      $this->apiToken = $apiToken;
  }

  public function createPrediction($prompt)
  {
      $data = [
          'input' => [
              'prompt' => $prompt
          ]
      ];

      $ch = curl_init($this->apiUrl);

      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, [
          'Authorization: Bearer ' . $this->apiToken,
          'Content-Type: application/json'
      ]);
      curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_FAILONERROR, false); 

      $response = curl_exec($ch);

      if ($response === false) {
          $error = curl_error($ch);
          curl_close($ch);
          throw new Exception('cURL Error: ' . $error);
      }

      $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
      curl_close($ch);

      $decodedResponse = json_decode($response, true);

      if ($httpCode >= 400) {
          $errorMessage = isset($decodedResponse['error']) ? $decodedResponse['error'] : 'Unknown error';
          throw new Exception('API Error: ' . $errorMessage);
      }

      return $decodedResponse;
  }
}
/*
<?php
$replicateApiToken = 'YOUR_REPLICATE_API_TOKEN';

$api = new ReplicateAPI($replicateApiToken);

$fluxPrompt = 'black forest gateau cake spelling out the words "FLUX SCHNELL", tasty, food photography, dynamic shot';

try {
  $response = $api->createPrediction($fluxPrompt);

  echo "Prediction ID: " . $response['id'] . PHP_EOL;
  echo "Status: " . $response['status'] . PHP_EOL;
} catch (Exception $e) {
  echo 'Error: ' . $e->getMessage();
}

 */

?>
