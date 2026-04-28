<?php

namespace App\Jobs;

use App\Models\UserAnswer;
use App\Services\AIService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;

class AnalyzeAnswerJob implements ShouldQueue
{
    use Queueable;
    public int $tries = 3;
    public int $backoff = 10;
    public function __construct(
        public UserAnswer $answer,
        public string $type,
        public ?string $extension = null,
    ) {}
    public function handle(): void
    {
        $ai = new AIService();
        $question  = $this->answer->question;
        $maxPoints = $question->points_max ?? 1;
        if ($this->type === 'file') {
            if (!Storage::disk('public')->exists($this->answer->answer)) {
                $this->answer->update([
                    'ai_feedback' => 'Файл не найден.',
                    'points' => 0,
                ]);
                return;
            }
            $content = substr(Storage::disk('public')->get($this->answer->answer), 0, 3000);
            $result = $ai->analyzeCode($question->title, $content, $maxPoints);
        } else {
            $content = $this->answer->answer ?? '';
            $result = $ai->analyzeTextAnswer($question->title, $content, $maxPoints);
        }
        if ($result) {
            $this->answer->update([
                'ai_score' => $result['score'] ?? 0,
                'ai_feedback' => $result['feedback'] ?? null,
                'points' => $result['score'] ?? 0,
            ]);
        } else {
            $this->release(60);
        }
    }
}
