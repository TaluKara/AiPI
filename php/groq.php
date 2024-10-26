<?php

class ApiClient {
  private $apiKey;
  private $model;

  public function __construct($groq_api, $groq_model) {
    $this->apiKey = $groq_api;
    $this->model = $groq_model;
  }

  public function getCompletion($groq_prompt) {
    $url = "https://api.groq.com/openai/v1/chat/completions";
    
    $data = [
      'messages' => [
        [
          'role' => 'user',
          'content' => $groq_prompt
        ]
      ],
      'model' => $this->model
    ];

    $ch = curl_init($url);
    
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
      'Authorization: Bearer ' . $this->apiKey,
      'Content-Type: application/json'
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    
    $response = curl_exec($ch);
    
    if (curl_errno($ch)) {
      // troubleshooting 
      echo 'Curl error: ' . curl_error($ch);
      return null;
    }

    curl_close($ch);

    return json_decode($response, true);
  }
}

/*
$groq_api = 'YOUR_API_KEY';
$groq_model = 'llama3-8b-8192';
$groq_prompt = "Explain the importance of fast language models";

$client = new ApiClient($groq_api, $groq_model);

$response = $client->getCompletion($groq_prompt);

print_r($response);

 */
?>
