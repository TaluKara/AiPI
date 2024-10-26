<?php

class AnthropicAPI {
  private $apiKey;
  private $apiUrl = 'https://api.anthropic.com/v1/messages';
  private $version = '2023-06-01';

  public function __construct($apiKey) {
    $this->apiKey = $apiKey;
  }

  public function sendMessage($prompt, $model, $maxTokens = 1024) {
    $data = [
      'model' => $model,
      'max_tokens' => $maxTokens,
      'messages' => [
        ['role' => 'user', 'content' => $prompt]
      ]
    ];

    $ch = curl_init($this->apiUrl);
    
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
      "x-api-key: $this->apiKey",
      "anthropic-version: $this->version",
      "content-type: application/json"
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
      echo 'Curl error: ' . curl_error($ch);
    }

    curl_close($ch);

    return json_decode($response, true);
  }
}

/*
$claude_api = 'YOUR_ANTHROPIC_API_KEY'; 
$claude_prompt = "Hello, world";
$claude_model = "claude-3-5-sonnet-20240620";

$anthropicApi = new AnthropicAPI($claude_api);
$response = $anthropicApi->sendMessage($claude_prompt, $claude_model);

print_r($response);
 */

?>
