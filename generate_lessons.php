<?php
// Script to generate lesson JSON files for Quranic words
// This script creates lesson files in batches of 100 words each

// Sample Quranic words list (in reality, this would be a comprehensive list of 6000+ words)
// For demonstration, using a small sample. In production, replace with full list.
$sample_words = [
    // Lesson 1-100: Basic words (already created)
    // Lesson 101-200: More words
    ["word" => "رحمة", "meaning" => "رحمت", "type" => "noun"],
    ["word" => "غفران", "meaning" => "مغفرت", "type" => "noun"],
    ["word" => "توفیق", "meaning" => "توفیق", "type" => "noun"],
    ["word" => "هدى", "meaning" => "ہدایت", "type" => "noun"],
    ["word" => "نصر", "meaning" => "نصرت", "type" => "noun"],
    ["word" => "فتح", "meaning" => "فتح", "type" => "noun"],
    ["word" => "ظفر", "meaning" => "ظفر", "type" => "noun"],
    ["word" => "نجاح", "meaning" => "نجاح", "type" => "noun"],
    ["word" => "فلاح", "meaning" => "فلاح", "type" => "noun"],
    ["word" => "سعادة", "meaning" => "سعادت", "type" => "noun"],
    // Add more words here... (up to 6000+)
];

// Function to generate quiz questions for a set of words
function generate_quiz($words) {
    $quiz = [];
    foreach ($words as $index => $word) {
        $options = [$word['meaning']];
        // Add 3 wrong options (in reality, from other words)
        $wrong_options = ["گمراہی", "عذاب", "ظلم", "جہالت"]; // Sample wrongs
        shuffle($wrong_options);
        $options = array_merge($options, array_slice($wrong_options, 0, 3));
        shuffle($options);
        $correct_index = array_search($word['meaning'], $options);

        $quiz[] = [
            "question" => "'{$word['word']}' کا معنی کیا ہے؟",
            "options" => $options,
            "correct" => $correct_index,
            "explanation" => "'{$word['word']}' کا معنی {$word['meaning']} ہے۔"
        ];
    }
    return array_slice($quiz, 0, 10); // 10 questions per lesson
}

// Generate lessons in batches
$batch_size = 100;
$total_words = count($sample_words);
$lesson_number = 101; // Starting from 101 since 1-100 are created

for ($i = 0; $i < $total_words; $i += $batch_size) {
    $batch_words = array_slice($sample_words, $i, $batch_size);
    $start = $i + 1;
    $end = min($i + $batch_size, $total_words);

    $lesson = [
        "title" => "قرآن کے بنیادی الفاظ ({$start}-{$end})",
        "content" => "قرآن کریم کے اگلے 100 الفاظ جو بنیادی اسم، افعال، اور احرف پر مشتمل ہیں۔ یہ الفاظ قرآن کی مختلف سورتوں میں بار بار آتے ہیں اور ان کی سمجھ قرآن کی تفہیم کے لیے ضروری ہے۔",
        "examples" => array_map(function($w) { return "{$w['word']} - {$w['meaning']}"; }, array_slice($batch_words, 0, 10)),
        "quiz" => generate_quiz($batch_words)
    ];

    $filename = "lesson{$lesson_number}.json";
    file_put_contents($filename, json_encode($lesson, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    echo "Generated {$filename}\n";
    $lesson_number++;
}

echo "Lesson generation complete. Run this script with a full list of 6000+ Quranic words to generate all lessons.\n";
?>
