<?php

class OpenAI {
  private $apiKey;
  private $baseUrl = 'https://api.openai.com/v1/chat/completions';

  public function __construct($openai_api) {
    $this->apiKey = $openai_api;
  }

  public function createCompletion($openai_model, $openai_prompt) {
    $messages = [
      [
        'role' => 'system',
        'content' => 'You are a helpful assistant.'
      ],
      [
        'role' => 'user',
        'content' => $openai_prompt
      ]
    ];

    $data = [
      'model' => $openai_model,
      'messages' => $messages
    ];

    $ch = curl_init($this->baseUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
      'Content-Type: application/json',
      'Authorization: Bearer ' . $this->apiKey,
    ]);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
      // troubleshooting
      echo 'cURL Error: ' . curl_error($ch);
    }

    curl_close($ch);
    return json_decode($response, true);
  }
}


/*

$openai_api = 'YOUR_API_KEY';
$openai_model = 'gpt-4';
$openai_prompt = 'Hello!';

$openAI = new OpenAI($openai_api);

$response = $openAI->createCompletion($openai_model, $openai_prompt);

print_r($response);
 */
?>
