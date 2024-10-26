<?php

class ApiClient
{
  private $apiKey;
  private $baseUrl;
  private $voiceId;
  private $model;
  private $prompt;

  public function __construct($apiKey, $baseUrl = 'https://api.elevenlabs.io/v1')
  {
    $this->apiKey = $apiKey;
    $this->baseUrl = rtrim($baseUrl, '/');
  }
  public function setVoiceId($voiceId)
  {
    $this->voiceId = $voiceId;
  }
  public function setModel($model)
  {
    $this->model = $model;
  }
  public function setPrompt($prompt)
  {
    $this->prompt = $prompt;
  }

  public function textToSpeech()
  {
    if (empty($this->voiceId)) {
      throw new Exception('Voice ID is not set.');
    }

    $url = "{$this->baseUrl}/text-to-speech/{$this->voiceId}";

    $data = [
      'prompt' => $this->prompt,
      'model' => $this->model
    ];

    $headers = [
      'Content-Type: application/json',
      "Authorization: Bearer {$this->apiKey}"
    ];

    return $this->sendPostRequest($url, $data, $headers);
  }

  private function sendPostRequest($url, $data, $headers = [])
  {
    $ch = curl_init($url);

    $payload = json_encode($data);

    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // we will use this for debugging purposes
    // curl_setopt($ch, CURLOPT_VERBOSE, true);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if (curl_errno($ch)) {
      $error_msg = curl_error($ch);
      curl_close($ch);
      throw new Exception("cURL error: {$error_msg}");
    }

    curl_close($ch);

    if ($httpCode >= 200 && $httpCode < 300) {
      return json_decode($response, true);
    } else {
      throw new Exception("API request failed. status  {$httpCode}: {$response}");
    }
  }
}
/*
<?php

try {
  $apiKey = 'YOUR_API_KEY_HERE';
  $client = new ApiClient($apiKey);

  $client->setVoiceId('your_voice_id');
  $client->setModel('your_model');
  $client->setPrompt('Merhaba, nasılsınız?');

  $response = $client->textToSpeech();

  print_r($response);

} catch (Exception $e) {
  // troubleshooting
  echo 'Error: ' . $e->getMessage();
}

*/


?>
