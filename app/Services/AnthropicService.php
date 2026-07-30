<?php

namespace App\Services;

class AnthropicService
{
    protected string $apiKey;
    // protected string $model = 'gemini-2.5-flash-lite';
    protected string $model = 'gemini-3.1-flash-lite';
    // protected string $model = 'gemini-2.5-flash-lite-preview-09-2025';

    public function __construct()
    {
        $this->apiKey = env('GEMINI_API_KEY', '');
    }

    /**
     * @param string $systemPrompt
     * @param array $messages [['role'=>'user'|'assistant', 'content'=>'...']]
     */
    public function chat(string $systemPrompt, array $messages): string
    {
        if (empty($this->apiKey)) {
            return 'AI service is not configured.';
        }

        $contents = [];

        foreach ($messages as $message) {
            $contents[] = [
                'role' => $message['role'] === 'assistant' ? 'model' : 'user',
                'parts' => [
                    [
                        'text' => $message['content']
                    ]
                ]
            ];
        }

        $payload = [
            'systemInstruction' => [
                'parts' => [
                    [
                        'text' => $systemPrompt
                    ]
                ]
            ],
            'contents' => $contents,
            'generationConfig' => [
                'temperature' => 0.7,
                'maxOutputTokens' => 2000,
            ]
        ];

        // FIXED: Added proper sprintf placeholders (%s) and appended the API key query parameter
        $url = sprintf(
            'https://generativelanguage.googleapis.com/v1beta/models/%s:generateContent?key=%s',
            $this->model,
            $this->apiKey
        );

        $ch = curl_init($url);

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
            ],
            CURLOPT_TIMEOUT => 30,
        ]);

        $response = curl_exec($ch);
        $error = curl_error($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        if ($error) {
            log_message('error', 'Gemini cURL Error: ' . $error);
            return 'Sorry, something went wrong contacting the AI.';
        }

        if ($status >= 400) {
            log_message('error', "Gemini HTTP {$status}: {$response}");
            return "Gemini returned an error. {$status}";
        }

        $data = json_decode($response, true);

        if (!empty($data['candidates'][0]['content']['parts'])) {
            $text = '';

            foreach ($data['candidates'][0]['content']['parts'] as $part) {
                $text .= $part['text'] ?? '';
            }

            return trim($text);
        }

        if (!empty($data['promptFeedback']['blockReason'])) {
            return 'Request blocked: ' . $data['promptFeedback']['blockReason'];
        }

        log_message('error', 'Unexpected Gemini response: ' . $response);

        return 'Sorry, I could not generate a response.';
    }

    
}