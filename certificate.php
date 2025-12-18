<?php
session_start();

// Check if user has passed and has a name
if (!isset($_SESSION['percentage']) || $_SESSION['percentage'] < 41 || empty($_SESSION['user_name'])) {
    header('Location: index.php');
    exit;
}

// Get lesson title
$lesson_file = '';
$lesson_title = 'سبق';

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
    $lesson_title = $lesson_data['title'];
}

$date = date('d/m/Y');
$user_name = $_SESSION['user_name'];
$score = $_SESSION['score'] . '/' . $_SESSION['total_questions'];
$percentage = $_SESSION['percentage'] . '%';
?>

<!DOCTYPE html>
<html lang="ur">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>سرٹیفکیٹ - قرآن عربی ٹیوٹر</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            direction: rtl;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .certificate {
            max-width: 800px;
            margin: 0 auto;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            text-align: center;
            color: white;
            position: relative;
            overflow: hidden;
        }
        .certificate::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><text fill="rgba(255,255,255,0.1)" font-size="20" y="50%">★</text></svg>') repeat;
            opacity: 0.1;
        }
        .certificate > * {
            position: relative;
            z-index: 1;
        }
        .header {
            font-size: 24px;
            margin-bottom: 20px;
            font-weight: bold;
        }
        .title {
            font-size: 36px;
            margin: 20px 0;
            font-weight: bold;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        .recipient {
            font-size: 28px;
            margin: 20px 0;
            font-weight: bold;
            color: #ffd700;
        }
        .achievement {
            font-size: 20px;
            margin: 15px 0;
        }
        .details {
            font-size: 18px;
            margin: 10px 0;
        }
        .date {
            font-size: 16px;
            margin-top: 30px;
            font-style: italic;
        }
        .signature {
            margin-top: 40px;
            font-size: 18px;
        }
        .print-btn {
            background: #27ae60;
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 18px;
            margin-top: 20px;
            text-decoration: none;
            display: inline-block;
        }
        .print-btn:hover {
            background: #229954;
        }
        @media print {
            body {
                background: white;
                padding: 0;
            }
            .certificate {
                box-shadow: none;
                margin: 0;
                padding: 20px;
            }
            .print-btn {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="certificate">
        <div class="header">قرآن عربی ٹیوٹر</div>
        <div class="title">سرٹیفکیٹ کی تکمیل</div>
        <div class="achievement">یہ سرٹیفکیٹ اس بات کی گواہی دیتا ہے کہ</div>
        <div class="recipient"><?php echo htmlspecialchars($user_name); ?></div>
        <div class="achievement">نے کامیابی سے مکمل کیا ہے</div>
        <div class="details">سبق: <?php echo htmlspecialchars($lesson_title); ?></div>
        <div class="details">اسکور: <?php echo $score; ?> (<?php echo $percentage; ?>)</div>
        <div class="date">تاریخ: <?php echo $date; ?></div>
        <div class="signature">قرآن عربی ٹیوٹر ٹیم</div>
    </div>
    <div style="text-align: center; margin-top: 20px;">
        <button onclick="window.print()" class="print-btn">پرنٹ کریں / ڈاؤن لوڈ کریں</button>
    </div>
</body>
</html>
