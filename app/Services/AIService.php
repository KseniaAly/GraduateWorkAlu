<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIService{
    protected $apiKey;
    protected $model;
    public function __construct(){
        $this->apiKey = config('services.openrouter.key');
        $this->model = config('services.openrouter.model');
    }
    private function sendRequest(string $prompt) : ?array{
        try {
            $response = Http::timeout(30)->withHeaders([
                'Authorization' => 'Bearer '.$this->apiKey,
                'HTTP-Referer' => config('app.url'),
                'X-Title' => 'Laravel Test Checker',
            ])->post('https://openrouter.ai/api/v1/chat/completions',[
                'model' => $this->model,
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'Ты строгий преподаватель. Отвечай ТОЛЬКО валидным JSON, без пояснений.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'temperature' => 0.1
            ]);
            if ($response->failed()) {
                Log::error('OpenRouter FAILED', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return null;
            }
            $raw = $response->body();
            Log::info('OpenRouter RAW RESPONSE', [
                'raw' => $raw
            ]);
            return json_decode($raw, true);
        } catch (\Throwable $error){
            Log::error('AIService: исключение', ['message' => $error->getMessage()]);
            return null;
        }
    }
    private function parseContent(?array $response): ?array
    {
        $content = $response['choices'][0]['message']['content'] ?? null;
        if (!$content) {
            Log::error('AI empty content response', $response ?? []);
            return null;
        }
        $content = preg_replace('/^```json\s*/i', '', trim($content));
        $content = preg_replace('/```$/', '', trim($content));
        $decoded = json_decode($content, true);
        if (!$decoded) {
            Log::error('AI JSON decode failed', ['raw' => $content]);
            return null;
        }
        return $decoded;
    }
    public function analyzeTextAnswer(string $question, string $answer, int $maxPoints = 5): ?array
    {
        $prompt = <<<PROMPT
    Вопрос: {$question}

    Ответ студента: {$answer}

    Оцени ответ по критериям:
    1. Правильность
    2. Полнота

    Максимальный балл: {$maxPoints}

    Верни ТОЛЬКО валидный JSON:
    {
      "score": <целое число от 0 до {$maxPoints}>,
      "correct": <true или false>,
      "feedback": "<комментарий на русском, 1-2 предложения>"
    }
    PROMPT;
        $response = $this->sendRequest($prompt);
        return $this->parseContent($response);
    }
    public function analyzeCode(string $task, string $code, int $maxPoints = 10): ?array
    {
        $prompt = <<<PROMPT
    Задание: {$task}

    Код студента:
    {$code}

    Проверь:
    1. Работает ли код
    2. Выполняет ли задание
    3. Есть ли логические или синтаксические ошибки

    Максимальный балл: {$maxPoints}

    Верни ТОЛЬКО валидный JSON:
    {
      "score": <целое число от 0 до {$maxPoints}>,
      "correct": <true или false>,
      "issues": ["<проблема 1>", "<проблема 2>"],
      "feedback": "<общий комментарий на русском, 2-3 предложения>"
    }
    PROMPT;
        $response = $this->sendRequest($prompt);
        return $this->parseContent($response);
    }
}
