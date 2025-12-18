<?php
session_start();
require_once 'ai_controller.php';

// Initialize session variables if not set
if (!isset($_SESSION['user_name'])) {
    $_SESSION['user_name'] = '';
}
if (!isset($_SESSION['current_lesson'])) {
    $_SESSION['current_lesson'] = 1;
}
if (!isset($_SESSION['view'])) {
    $_SESSION['view'] = 'name_input';
}
if (!isset($_SESSION['score'])) {
    $_SESSION['score'] = 0;
}
if (!isset($_SESSION['total_questions'])) {
    $_SESSION['total_questions'] = 0;
}
if (!isset($_SESSION['current_question'])) {
    $_SESSION['current_question'] = 0;
}
if (!isset($_SESSION['answers'])) {
    $_SESSION['answers'] = [];
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['submit_name'])) {
        $_SESSION['user_name'] = trim($_POST['user_name']);
        $_SESSION['view'] = 'lesson';
    } elseif (isset($_POST['start_quiz'])) {
        $_SESSION['view'] = 'quiz';
        $_SESSION['current_question'] = 0;
        $_SESSION['answers'] = [];
    } elseif (isset($_POST['submit_question'])) {
        // Save answer for current question
        $current_q = $_SESSION['current_question'];
        $_SESSION['answers'][$current_q] = $_POST['q' . $current_q] ?? null;
        $_SESSION['current_question']++;

        // Check if quiz is complete
        $lesson_file = '';
        $word_lesson_file = 'lesson' . $_SESSION['current_lesson'] . '.json';
        if (file_exists($word_lesson_file)) {
            $lesson_file = $word_lesson_file;
        } else {
            $surah_number = $_SESSION['current_lesson'] - 1000;
            if ($surah_number >= 1 && $surah_number <= 114) {
                $surah_lesson_file = 'lesson_s' . $surah_number . '.json';
                if (file_exists($surah_lesson_file)) {
                    $lesson_file = $surah_lesson_file;
                }
            }
        }
        if ($lesson_file && file_exists($lesson_file)) {
            $lesson_data = json_decode(file_get_contents($lesson_file), true);
            $total_questions = count($lesson_data['quiz']);
            if ($_SESSION['current_question'] >= $total_questions) {
                // Calculate score
                $quiz = $lesson_data['quiz'];
                $correct = 0;
                foreach ($quiz as $index => $question) {
                    if (isset($_SESSION['answers'][$index]) && $_SESSION['answers'][$index] == $question['correct']) {
                        $correct++;
                    }
                }
                $_SESSION['score'] = $correct;
                $_SESSION['total_questions'] = $total_questions;
                $_SESSION['percentage'] = round(($correct / $total_questions) * 100);

                // AI analysis and feedback
                $lesson_type = $_SESSION['current_lesson'] > 1000 ? 'surah' : 'word';
                $ai_feedback_data = $ai_controller->analyze_performance($correct, $total_questions, $lesson_type);
                $_SESSION['ai_feedback'] = $ai_feedback_data['message'];
                $_SESSION['ai_suggestion'] = $ai_feedback_data['suggestion'];

                $_SESSION['view'] = 'result';
            }
        }
    } elseif (isset($_POST['submit_quiz'])) {
        // Calculate score
        $lesson_file = '';
        // Check for word lessons first (1-1000+)
        $word_lesson_file = 'lesson' . $_SESSION['current_lesson'] . '.json';
        if (file_exists($word_lesson_file)) {
            $lesson_file = $word_lesson_file;
        } else {
            // Check for Surah lessons (1001+ maps to s1, s2, etc.)
            $surah_number = $_SESSION['current_lesson'] - 1000;
            if ($surah_number >= 1 && $surah_number <= 114) {
                $surah_lesson_file = 'lesson_s' . $surah_number . '.json';
                if (file_exists($surah_lesson_file)) {
                    $lesson_file = $surah_lesson_file;
                }
            }
        }
        if ($lesson_file && file_exists($lesson_file)) {
            $lesson_data = json_decode(file_get_contents($lesson_file), true);
            $quiz = $lesson_data['quiz'];
            $correct = 0;
            $total = count($quiz);
            foreach ($quiz as $index => $question) {
                if (isset($_POST['q' . $index]) && $_POST['q' . $index] == $question['correct']) {
                    $correct++;
                }
            }
            $_SESSION['score'] = $correct;
            $_SESSION['total_questions'] = $total;
            $_SESSION['percentage'] = round(($correct / $total) * 100);

            // AI analysis and feedback
            $lesson_type = $_SESSION['current_lesson'] > 1000 ? 'surah' : 'word';
            $ai_feedback_data = $ai_controller->analyze_performance($correct, $total, $lesson_type);
            $_SESSION['ai_feedback'] = $ai_feedback_data['message'];
            $_SESSION['ai_suggestion'] = $ai_feedback_data['suggestion'];

            $_SESSION['view'] = 'result';
        }
    }
        $_SESSION['view'] = 'quiz';
        $_SESSION['answers'] = [];
    } elseif (isset($_POST['retry'])) {
        $_SESSION['view'] = 'quiz';
        $_SESSION['current_question'] = 0;
        $_SESSION['answers'] = [];
        $_SESSION['score'] = 0;
        $_SESSION['total_questions'] = 0;
    } elseif (isset($_POST['next_lesson'])) {
        $_SESSION['current_lesson']++;
        $_SESSION['view'] = 'lesson';
        $_SESSION['score'] = 0;
        $_SESSION['total_questions'] = 0;
        $_SESSION['current_question'] = 0;
        $_SESSION['answers'] = [];
    }
}

// Load current lesson data
$lesson_file = '';
$lesson_data = null;

// Check for word lessons first (1-1000+)
$word_lesson_file = 'lesson' . $_SESSION['current_lesson'] . '.json';
if (file_exists($word_lesson_file)) {
    $lesson_file = $word_lesson_file;
} else {
    // Check for Surah lessons (101+ maps to s1, s2, etc.)
    $surah_number = $_SESSION['current_lesson'] - 1000; // e.g., lesson 1001 = s1, 1002 = s2
    if ($surah_number >= 1 && $surah_number <= 114) {
        $surah_lesson_file = 'lesson_s' . $surah_number . '.json';
        if (file_exists($surah_lesson_file)) {
            $lesson_file = $surah_lesson_file;
        }
    }
}

if ($lesson_file && file_exists($lesson_file)) {
    $lesson_data = json_decode(file_get_contents($lesson_file), true);
}

// Urdu feedback messages
$roasts = [
    'ultra_savage' => [
        "بھائی/بہن، یہ نتیجہ نہیں، یہ امتحان کی قبرستان ہے! سبق کھولنا بھول گئے۔",
        "اتنی غلطیاں؟ اگر یہ کھیل ہوتا تو تم ری سیٹ ہو جاتے!",
        "محنت کہاں ہے؟ نیند کے ماسٹر لگ رہے ہو!",
        "سب سبق چھوڑ کے بیٹھے تھے یا دماغ چھپایا ہوا تھا؟ ہر جواب غلط!",
        "یہ فیل ہونا نہیں، یہ ٹرافی ہے تمہاری سستی کی!"
    ],
    'brutal' => [
        "کتنی غلطیاں؟ استاد بھی تم پر شرمائے بغیر نہیں رہ سکتا!",
        "اگر یہ عربی امتحان ہوتا تو نمبر صفر ہوتا!",
        "تم نے سبق دیکھا بھی نہیں، جواب تو اندھا دھند مارا!",
        "کیا سبق کھولنا مشکل تھا یا دماغ کا بریک لگ گیا؟"
    ],
    'mild_sarcastic' => [
        "چلو کچھ حاصل ہوا، لیکن بہتر کر سکتے تھے!",
        "ٹھیک ہے، لیکن محنت کی ضرورت ہے!",
        "اچھا ہے، لیکن اگلی بار پوری توجہ دو!"
    ]
];
$praises = [
    "بہت اچھا! آپ نے بہترین کام کیا۔",
    "واو! آپ نے تو کمال کر دیا۔",
    "شاباش! آپ کی کوشش قابل تعریف ہے۔",
    "بہترین! آپ نے سب کچھ درست کیا۔"
];

// Determine feedback
$feedback = '';
if ($_SESSION['view'] === 'result') {
    $percentage = $_SESSION['percentage'];
    if ($percentage >= 41) {
        $feedback = $praises[array_rand($praises)];
    } elseif ($percentage >= 20) {
        $feedback = $roasts['mild_sarcastic'][array_rand($roasts['mild_sarcastic'])];
    } elseif ($percentage >= 10) {
        $feedback = $roasts['brutal'][array_rand($roasts['brutal'])];
    } else {
        $feedback = $roasts['ultra_savage'][array_rand($roasts['ultra_savage'])];
    }
}
?>

<!DOCTYPE html>
<html lang="ur">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>قرآن عربی ٹیوٹر</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
            direction: rtl;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 0;
            padding: 20px;
            color: #333;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .container {
            max-width: 900px;
            width: 100%;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            animation: fadeIn 0.5s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        h1 {
            text-align: center;
            color: #2c3e50;
            font-weight: 700;
            font-size: 2.5em;
            margin-bottom: 10px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #34495e;
            font-weight: 600;
            font-size: 1.8em;
            margin-bottom: 20px;
        }
        .lesson-content {
            margin-bottom: 30px;
            line-height: 1.6;
            font-size: 1.1em;
        }
        .examples {
            background: linear-gradient(135deg, #ecf0f1 0%, #bdc3c7 100%);
            padding: 15px;
            border-radius: 10px;
            margin: 15px 0;
            border-left: 5px solid #3498db;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .quiz-form {
            margin-top: 30px;
        }
        .question {
            margin-bottom: 20px;
            background: #f8f9fa;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .options {
            margin-left: 20px;
        }
        .option {
            margin: 10px 0;
            display: flex;
            align-items: center;
        }
        .option input[type="radio"] {
            margin-left: 10px;
            transform: scale(1.2);
        }
        .btn {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 25px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 500;
            margin: 15px 10px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(52, 152, 219, 0.3);
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(52, 152, 219, 0.4);
        }
        .btn-danger {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
        }
        .btn-danger:hover {
            box-shadow: 0 6px 20px rgba(231, 76, 60, 0.4);
        }
        .btn-success {
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
        }
        .btn-success:hover {
            box-shadow: 0 6px 20px rgba(39, 174, 96, 0.4);
        }
        .result {
            text-align: center;
            margin: 30px 0;
            padding: 30px;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .success {
            color: #27ae60;
            font-weight: 600;
        }
        .failure {
            color: #e74c3c;
            font-weight: 600;
        }
        input[type="text"] {
            width: 100%;
            padding: 15px;
            border: 2px solid #bdc3c7;
            border-radius: 10px;
            font-size: 16px;
            transition: border-color 0.3s ease;
            margin-bottom: 20px;
        }
        input[type="text"]:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        }
        @media (max-width: 600px) {
            body {
                padding: 10px;
            }
            .container {
                padding: 20px;
            }
            .btn {
                width: 100%;
                margin: 10px 0;
            }
            h1 {
                font-size: 2em;
            }
            h2 {
                font-size: 1.5em;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>قرآن عربی ٹیوٹر</h1>

        <?php if ($_SESSION['view'] === 'name_input'): ?>
            <h2>خوش آمدید</h2>
            <p>برائے مہربانی اپنا نام درج کریں:</p>
            <form method="post">
                <input type="text" name="user_name" required placeholder="اپنا نام یہاں لکھیں" style="padding: 10px; font-size: 16px; width: 100%; margin-bottom: 10px;">
                <button type="submit" name="submit_name" class="btn">شروع کریں</button>
            </form>

        <?php elseif ($_SESSION['view'] === 'lesson' && $lesson_data): ?>
            <h2><?php echo $lesson_data['title']; ?></h2>
            <div class="lesson-content">
                <p><?php echo $lesson_data['content']; ?></p>
                <h3>مثالیں:</h3>
                <?php foreach ($lesson_data['examples'] as $example): ?>
                    <div class="examples"><?php echo $example; ?></div>
                <?php endforeach; ?>
            </div>
            <form method="post">
                <button type="submit" name="start_quiz" class="btn">کوئز شروع کریں</button>
            </form>

        <?php elseif ($_SESSION['view'] === 'quiz' && $lesson_data): ?>
            <h2>کوئز: <?php echo $lesson_data['title']; ?></h2>
            <p>سوال <?php echo ($_SESSION['current_question'] + 1); ?> از <?php echo count($lesson_data['quiz']); ?></p>
            <form method="post" class="quiz-form">
                <?php
                $current_q_index = $_SESSION['current_question'];
                $question = $lesson_data['quiz'][$current_q_index];
                ?>
                <div class="question">
                    <p><?php echo ($current_q_index + 1) . '. ' . $question['question']; ?></p>
                    <div class="options">
                        <?php foreach ($question['options'] as $opt_index => $option): ?>
                            <label class="option">
                                <input type="radio" name="q<?php echo $current_q_index; ?>" value="<?php echo $opt_index; ?>" required>
                                <?php echo $option; ?>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
                <button type="submit" name="submit_question" class="btn">اگلا سوال</button>
            </form>

        <?php elseif ($_SESSION['view'] === 'result'): ?>
            <div class="result">
                <h2>نتیجہ</h2>
                <p>آپ کا اسکور: <?php echo $_SESSION['score']; ?>/<?php echo $_SESSION['total_questions']; ?> (<?php echo $_SESSION['percentage']; ?>%)</p>
                <p class="<?php echo $_SESSION['percentage'] >= 41 ? 'success' : 'failure'; ?>"><?php echo $_SESSION['ai_feedback']; ?></p>
                <?php if ($_SESSION['percentage'] >= 41): ?>
                    <p><a href="certificate.php" target="_blank" class="btn btn-success" style="text-decoration: none;">سرٹیفکیٹ ڈاؤن لوڈ کریں</a></p>
                    <form method="post" style="display: inline;">
                        <button type="submit" name="next_lesson" class="btn btn-success">اگلا سبق</button>
                    </form>
                <?php else: ?>
                    <form method="post" style="display: inline;">
                        <button type="submit" name="retry" class="btn btn-danger">دوبارہ کوشش کریں</button>
                    </form>
                <?php endif; ?>
            </div>

        <?php else: ?>
            <p>سبق دستیاب نہیں ہے۔</p>
        <?php endif; ?>
    </div>
</body>
</html>
