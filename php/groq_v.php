<?php

class GroqApiClient
{
    private $apiKey;
    private $imageUrl;
    private $imagePrompt;
    private $imageModel;
    private $apiEndpoint = "https://api.groq.com/openai/v1/chat/completions";

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function setImageUrl($url)
    {
        $this->imageUrl = $url;
    }

    public function setImagePrompt($prompt)
    {
        $this->imagePrompt = $prompt;
    }

    public function setImageModel($model)
    {
        $this->imageModel = $model;
    }
    public function sendRequest()
    {
        if (empty($this->apiKey)) {
            throw new Exception("API key is not set.");
        }

        if (empty($this->imageUrl)) {
            throw new Exception("Image URL is not set.");
        }

        if (empty($this->imagePrompt)) {
            throw new Exception("Image prompt is not set.");
        }

        if (empty($this->imageModel)) {
            throw new Exception("Image model is not set.");
        }

        $payload = [
            "messages" => [
                [
                    "role" => "user",
                    "content" => [
                        [
                            "type" => "text",
                            "text" => $this->imagePrompt
                        ],
                        [
                            "type" => "image_url",
                            "image_url" => [
                                "url" => $this->imageUrl
                            ]
                        ]
                    ]
                ],
                [
                    "role" => "assistant",
                    "content" => ""
                ]
            ],
            "model" => $this->imageModel,
            "temperature" => 1,
            "max_tokens" => 1024,
            "top_p" => 1,
            "stream" => false,
            "stop" => null
        ];

        $ch = curl_init($this->apiEndpoint);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);

        $headers = [
            "Content-Type: application/json",
            "Authorization: Bearer " . $this->apiKey
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
            curl_close($ch);
            throw new Exception("cURL error: " . $error_msg);
        }

        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $decoded_response = json_decode($response, true);

        if ($http_status !== 200) {
            throw new Exception("API request failed with status {$http_status}: " . $response);
        }

        return $decoded_response;
    }
}
/*
require_once 'GroqApiClient.php';
try {
    $groqApiKey = "YOUR_GROQ_API_KEY";

    $client = new GroqApiClient($groqApiKey);

    $client->setImageUrl("https://example.com/path/to/image.jpg");
    $client->setImagePrompt("What's in this image?");
    $client->setImageModel("llama-3.2-11b-vision-preview");

    $response = $client->sendRequest();

    echo "API Yanıtı:\n";
    print_r($response);

} catch (Exception $e) {
    echo "error: " . $e->getMessage();
}
*/
?>
