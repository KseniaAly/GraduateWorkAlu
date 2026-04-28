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
//    public int $tries = 3;
//    public int $backoff = 10;
    public function __construct(
        public int $answerId,
        public string $type,
        public ?string $extension = null,
    ) {}
    public function handle(): void
    {
        $ai = new AIService();
        $answer = UserAnswer::with('question')->find($this->answerId);
        if (!$answer) return;
        $question = $answer->question;
        if (!$question) {
            $answer->update([
                'ai_feedback' => 'Вопрос не найден',
                'points' => 0
            ]);
            return;
        }
        $maxPoints = $question->points_max ?? 1;
        $content = $answer->answer ?? '';
        if ($this->type === 'file') {
            if (!$content || !Storage::disk('public')->exists($content)){
                $answer->update([
                    'ai_feedback' => 'Файл не найден.',
                    'points' => 0
                ]);
                return;
            }
            $content = substr(Storage::disk('public')->get($content), 0, 3000);
            $result = $ai->analyzeCode($question->title, $content, $maxPoints);
        } else {
            $result = $ai->analyzeTextAnswer($question->title, $content, $maxPoints);
        }
        if (!$result){
            \Illuminate\Support\Facades\Log::error('AI returned null result', [
                'answer_id' => $answer->id,
                'type' => $this->type,
                'content' => $content ?? null
            ]);
            return;
        };
        $answer->update([
            'ai_score' => $result['score'] ?? 0,
            'ai_feedback' => $result['feedback'] ?? null,
            'points' => $result['score'] ?? 0,
        ]);
    }
}
