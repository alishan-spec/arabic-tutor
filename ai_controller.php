<?php
class QuranTutorAI {
    private $user_performance = [];
    private $lesson_difficulty = [];
    private $error_patterns = [];

    public function __construct() {
        // Initialize AI knowledge base
        $this->lesson_difficulty = [
            'basic' => ['arabic_alphabet', 'vowels'],
            'intermediate' => ['common_words', 'grammar_rules'],
            'advanced' => ['surah_analysis', 'deep_grammar']
        ];
    }

    public function analyze_performance($score, $total_questions, $lesson_type, $user_answers = []) {
        $percentage = ($score / $total_questions) * 100;
        $this->user_performance[] = [
            'score' => $percentage,
            'lesson_type' => $lesson_type,
            'timestamp' => time(),
            'answers' => $user_answers
        ];

        // Analyze patterns
        $this->analyze_patterns();

        return $this->generate_feedback($percentage, $lesson_type);
    }

    private function analyze_patterns() {
        $recent_scores = array_slice($this->user_performance, -5);
        $avg_score = array_sum(array_column($recent_scores, 'score')) / count($recent_scores);

        if ($avg_score < 30) {
            $this->error_patterns['low_performance'] = true;
        } elseif ($avg_score > 70) {
            $this->error_patterns['high_performance'] = true;
        }

        // Check for consistent mistakes
        $this->detect_consistent_errors();
    }

    private function detect_consistent_errors() {
        // Simple pattern detection for common mistakes
        $mistakes = [];
        foreach ($this->user_performance as $perf) {
            if (isset($perf['answers'])) {
                foreach ($perf['answers'] as $answer) {
                    if (!$answer['correct']) {
                        $mistakes[] = $answer['question_type'];
                    }
                }
            }
        }

        $mistake_counts = array_count_values($mistakes);
        arsort($mistake_counts);

        if (!empty($mistake_counts)) {
            $this->error_patterns['common_mistake'] = key($mistake_counts);
        }
    }

    public function generate_feedback($percentage, $lesson_type) {
        $feedback = [
            'message' => '',
            'suggestion' => '',
            'next_action' => '',
            'difficulty_adjustment' => 0
        ];

        if ($percentage >= 80) {
            $feedback['message'] = "بہت اچھا! آپ نے اس سبق میں کمال کر دیا۔";
            $feedback['suggestion'] = "اگلا سبق شروع کریں یا مزید چیلنجنگ مواد آزمائیں۔";
            $feedback['next_action'] = 'advance';
            $feedback['difficulty_adjustment'] = 1;
        } elseif ($percentage >= 60) {
            $feedback['message'] = "اچھا کام! تھوڑی سی محنت اور آپ مکمل کر لیں گے۔";
            $feedback['suggestion'] = "اسی سبق کو دہرائیں یا بنیادی تصورات پر نظر ثانی کریں۔";
            $feedback['next_action'] = 'review';
        } elseif ($percentage >= 40) {
            $feedback['message'] = "ٹھیک ہے، لیکن بہتری کی ضرورت ہے۔";
            $feedback['suggestion'] = "سبق کو دہرائیں اور مثالوں پر توجہ دیں۔";
            $feedback['next_action'] = 'retry';
            $feedback['difficulty_adjustment'] = -1;
        } else {
            $feedback['message'] = "محنت کی ضرورت ہے۔ یہ سبق آپ کے لیے مشکل ہے۔";
            $feedback['suggestion'] = "بنیادی سبق سے شروع کریں اور آہستہ آہستہ آگے بڑھیں۔";
            $feedback['next_action'] = 'regress';
            $feedback['difficulty_adjustment'] = -2;
        }

        // Add AI insights
        if (isset($this->error_patterns['common_mistake'])) {
            $feedback['message'] .= " AI تجزیہ: آپ کو {$this->error_patterns['common_mistake']} میں مشکلات کا سامنا ہے۔";
        }

        if (isset($this->error_patterns['low_performance'])) {
            $feedback['suggestion'] .= " AI سفارش: زیادہ وقت نکالیں اور روزانہ پریکٹس کریں۔";
        }

        return $feedback;
    }

    public function predict_next_lesson($current_lesson, $performance_history) {
        // Simple prediction based on performance
        $avg_performance = array_sum(array_column($performance_history, 'score')) / count($performance_history);

        if ($avg_performance > 75) {
            return $current_lesson + 1; // Advance faster
        } elseif ($avg_performance < 50) {
            return max(1, $current_lesson - 1); // Go back or stay
        } else {
            return $current_lesson + 1; // Normal progression
        }
    }

    public function handle_error($error_type, $context = []) {
        $responses = [
            'lesson_not_found' => "AI: سبق دستیاب نہیں ہے۔ بنیادی سبق سے شروع کریں۔",
            'quiz_error' => "AI: کوئز میں خرابی۔ دوبارہ کوشش کریں۔",
            'session_error' => "AI: سیشن میں مسئلہ۔ صفحہ ریفریش کریں۔",
            'file_error' => "AI: فائل لوڈ نہیں ہو سکی۔ انٹرنیٹ کنکشن چیک کریں یا آف لائن موڈ استعمال کریں۔"
        ];

        return $responses[$error_type] ?? "AI: نامعلوم خرابی۔ مدد کے لیے رابطہ کریں۔";
    }

    public function get_personalized_content($user_level, $lesson_type) {
        // Generate personalized content based on user level
        $content = [
            'beginner' => [
                'tips' => "آہستہ پڑھیں اور ہر لفظ کا مطلب سمجھیں۔",
                'examples' => "سادہ مثالوں سے شروع کریں۔"
            ],
            'intermediate' => [
                'tips' => "ترجمہ اور مفہوم دونوں پر توجہ دیں۔",
                'examples' => "قرآنی آیات کے ساتھ جوڑیں۔"
            ],
            'advanced' => [
                'tips' => "گہرائی میں جائیں اور سیاق و سباق سمجھیں۔",
                'examples' => "مختلف تراجم کا موازنہ کریں۔"
            ]
        ];

        return $content[$user_level] ?? $content['beginner'];
    }

    public function adapt_difficulty($current_difficulty, $performance) {
        if ($performance > 80) {
            return min(10, $current_difficulty + 1);
        } elseif ($performance < 40) {
            return max(1, $current_difficulty - 1);
        }
        return $current_difficulty;
    }
}

// Global AI instance
$ai_controller = new QuranTutorAI();
?>
