<?php

namespace App;

use GuzzleHttp\Client;

class ReportGenerator {
    private Client $client;
    private string $apiUrl = 'https://openrouter.ai/api/v1/chat/completions';
    private string $token = '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';

    public function __construct() {
        $this->client = new Client();
    }

    public function generateWithLLM(array $metrics): string {
        $prompt = "You are an expert performance analyst. Here are metrics: ";
        foreach ($metrics as $m) {
            $prompt .= sprintf("%s: max=%d, min=%d, avg=%.2f. ",
                $m['file'], $m['max'], $m['min'], $m['avg']);
        }
        $prompt .= "Provide a concise summary and recommendations.";

        try {
            $response = $this->client->post($this->apiUrl, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->token,
                    'Content-Type'  => 'application/json',
                    'HTTP-Referer'  => 'http://yourdomain.com',
                    'X-Title'       => 'Performance Report Generator'
                ],
                'body' => json_encode([
                    'model' => 'mistralai/mistral-7b-instruct',
                    'messages' => [
                        ['role' => 'system', 'content' => 'You are a helpful assistant and expert performance analyst.'],
                        ['role' => 'user', 'content' => $prompt]
                    ]
                ])
            ]);
            $body = json_decode($response->getBody()->getContents(), true);
            $analysis = $body['choices'][0]['message']['content'] ?? 'LLM response error.';
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            $analysis = 'LLM request failed: ' . $e->getMessage();
        }

        $html  = '<div class="metrics"><table>';
        $html .= '<tr><th>File</th><th>Max</th><th>Min</th><th>Avg</th></tr>';
        foreach ($metrics as $m) {
            $html .= sprintf('<tr><td>%s</td><td>%d</td><td>%d</td><td>%.2f</td></tr>',
                htmlspecialchars($m['file']), $m['max'], $m['min'], $m['avg']);
        }
        $html .= '</table><h3>LLM Analysis:</h3><p>' . nl2br(htmlspecialchars($analysis)) . '</p></div>';
        return $html;
    }
}