<?php
// Script to generate lesson JSON files for all 114 Surahs of the Quran
// This script creates one lesson per Surah with Urdu content, examples, and quiz

// List of all 114 Surahs with basic info (name, verses, place of revelation)
$surahs = [
    ["number" => 1, "name" => "الفاتحہ", "verses" => 7, "place" => "مکہ", "theme" => "نماز کی بنیاد اور اللہ کی تعریف"],
    ["number" => 2, "name" => "البقرہ", "verses" => 286, "place" => "مدینہ", "theme" => "ایمان، اعمال، اور اخلاقی تعلیمات"],
    ["number" => 3, "name" => "آل عمران", "verses" => 200, "place" => "مدینہ", "theme" => "اہل بیت اور جنگ احد"],
    ["number" => 4, "name" => "النساء", "verses" => 176, "place" => "مدینہ", "theme" => "خاندانی حقوق اور سماجی قوانین"],
    ["number" => 5, "name" => "المائدہ", "verses" => 120, "place" => "مدینہ", "theme" => "حلال اور حرام اور عہد نامہ"],
    ["number" => 6, "name" => "الأنعام", "verses" => 165, "place" => "مکہ", "theme" => "توحید اور مشرکین کی تردید"],
    ["number" => 7, "name" => "الأعراف", "verses" => 206, "place" => "مکہ", "theme" => "قصے انبیاء اور قیامت"],
    ["number" => 8, "name" => "الأنفال", "verses" => 75, "place" => "مدینہ", "theme" => "جنگ بدر اور غنیمت"],
    ["number" => 9, "name" => "التوبہ", "verses" => 129, "place" => "مدینہ", "theme" => "توبت اور جنگ"],
    ["number" => 10, "name" => "یونس", "verses" => 109, "place" => "مکہ", "theme" => "نوح اور موسیٰ کا قصہ"],
    ["number" => 11, "name" => "ہود", "verses" => 123, "place" => "مکہ", "theme" => "ہود اور صالح کا قصہ"],
    ["number" => 12, "name" => "یوسف", "verses" => 111, "place" => "مکہ", "theme" => "یوسف کا قصہ"],
    ["number" => 13, "name" => "الرعد", "verses" => 43, "place" => "مدینہ", "theme" => "قدرت الٰہی اور ایمان"],
    ["number" => 14, "name" => "إبراہیم", "verses" => 52, "place" => "مکہ", "theme" => "ابراہیم کا قصہ"],
    ["number" => 15, "name" => "الحجر", "verses" => 99, "place" => "مکہ", "theme" => "قدرت الٰہی اور شیاطین"],
    ["number" => 16, "name" => "النحل", "verses" => 128, "place" => "مکہ", "theme" => "شکر اور نعمتیں"],
    ["number" => 17, "name" => "الإسراء", "verses" => 111, "place" => "مکہ", "theme" => "معراج اور بنی اسرائیل"],
    ["number" => 18, "name" => "الكهف", "verses" => 110, "place" => "مکہ", "theme" => "اصحاب کہف اور موسیٰ"],
    ["number" => 19, "name" => "مريم", "verses" => 98, "place" => "مکہ", "theme" => "مریم اور یحییٰ"],
    ["number" => 20, "name" => "طه", "verses" => 135, "place" => "مکہ", "theme" => "موسیٰ اور فرعون"],
    ["number" => 21, "name" => "الأنبياء", "verses" => 112, "place" => "مکہ", "theme" => "انبیاء کا قصہ"],
    ["number" => 22, "name" => "الحج", "verses" => 78, "place" => "مدینہ", "theme" => "حج اور قیامت"],
    ["number" => 23, "name" => "المؤمنون", "verses" => 118, "place" => "مکہ", "theme" => "مومنین کی صفات"],
    ["number" => 24, "name" => "النور", "verses" => 64, "place" => "مدینہ", "theme" => "اخلاقی قوانین اور نور"],
    ["number" => 25, "name" => "الفرقان", "verses" => 77, "place" => "مکہ", "theme" => "فرقان اور قرآن"],
    ["number" => 26, "name" => "الشعراء", "verses" => 227, "place" => "مکہ", "theme" => "شعراء اور انبیاء"],
    ["number" => 27, "name" => "النمل", "verses" => 93, "place" => "مکہ", "theme" => "سليمان اور ملکہ سبأ"],
    ["number" => 28, "name" => "القصص", "verses" => 88, "place" => "مکہ", "theme" => "موسیٰ کا قصہ"],
    ["number" => 29, "name" => "العنکبوت", "verses" => 69, "place" => "مکہ", "theme" => "امتحان اور صبر"],
    ["number" => 30, "name" => "الروم", "verses" => 60, "place" => "مکہ", "theme" => "روم اور فارس کی جنگ"],
    ["number" => 31, "name" => "لقمان", "verses" => 34, "place" => "مکہ", "theme" => "لقمان کی حکمت"],
    ["number" => 32, "name" => "السجدة", "verses" => 30, "place" => "مکہ", "theme" => "سجدہ اور قرآن"],
    ["number" => 33, "name" => "الأحزاب", "verses" => 73, "place" => "مدینہ", "theme" => "احزاب کی جنگ"],
    ["number" => 34, "name" => "سبأ", "verses" => 54, "place" => "مکہ", "theme" => "سبأ اور سلیمان"],
    ["number" => 35, "name" => "فاطر", "verses" => 45, "place" => "مکہ", "theme" => "خالق اور قرآن"],
    ["number" => 36, "name" => "يس", "verses" => 83, "place" => "مکہ", "theme" => "قیامت اور قرآن"],
    ["number" => 37, "name" => "الصافات", "verses" => 182, "place" => "مکہ", "theme" => "فرشتے اور شیاطین"],
    ["number" => 38, "name" => "ص", "verses" => 88, "place" => "مکہ", "theme" => "داود اور سلیمان"],
    ["number" => 39, "name" => "الزمر", "verses" => 75, "place" => "مکہ", "theme" => "قیامت اور توبہ"],
    ["number" => 40, "name" => "غافر", "verses" => 85, "place" => "مکہ", "theme" => "موسى اور فرعون"],
    ["number" => 41, "name" => "فصلت", "verses" => 54, "place" => "مکہ", "theme" => "قرآن اور ایمان"],
    ["number" => 42, "name" => "الشورى", "verses" => 53, "place" => "مکہ", "theme" => "شوریٰ اور اسلام"],
    ["number" => 43, "name" => "الزخرف", "verses" => 89, "place" => "مکہ", "theme" => "مریم اور عیسیٰ"],
    ["number" => 44, "name" => "الدخان", "verses" => 59, "place" => "مکہ", "theme" => "دخان اور بنی اسرائیل"],
    ["number" => 45, "name" => "الجاثية", "verses" => 37, "place" => "مکہ", "theme" => "جاثیہ اور قدرت"],
    ["number" => 46, "name" => "الأحقاف", "verses" => 35, "place" => "مکہ", "theme" => "شعیب اور قوم"],
    ["number" => 47, "name" => "محمد", "verses" => 38, "place" => "مدینہ", "theme" => "جہاد اور ایمان"],
    ["number" => 48, "name" => "الفتح", "verses" => 29, "place" => "مدینہ", "theme" => "فتح مکہ"],
    ["number" => 49, "name" => "الحجرات", "verses" => 18, "place" => "مدینہ", "theme" => "اخلاقی تعلیمات"],
    ["number" => 50, "name" => "ق", "verses" => 45, "place" => "مکہ", "theme" => "قیامت اور قرآن"],
    ["number" => 51, "name" => "الذاريات", "verses" => 60, "place" => "مکہ", "theme" => "قیامت اور بادلوں"],
    ["number" => 52, "name" => "الطور", "verses" => 49, "place" => "مکہ", "theme" => "قیامت اور پہاڑ"],
    ["number" => 53, "name" => "النجم", "verses" => 62, "place" => "مکہ", "theme" => "نجم اور معراج"],
    ["number" => 54, "name" => "القمر", "verses" => 55, "place" => "مکہ", "theme" => "قمر اور قیامت"],
    ["number" => 55, "name" => "الرحمن", "verses" => 78, "place" => "مدینہ", "theme" => "رحمت اور نعمتیں"],
    ["number" => 56, "name" => "الواقعة", "verses" => 96, "place" => "مکہ", "theme" => "قیامت اور انسان"],
    ["number" => 57, "name" => "الحديد", "verses" => 29, "place" => "مدینہ", "theme" => "حدید اور ایمان"],
    ["number" => 58, "name" => "المجادلة", "verses" => 22, "place" => "مدینہ", "theme" => "مجادلہ اور طلاق"],
    ["number" => 59, "name" => "الحشر", "verses" => 24, "place" => "مدینہ", "theme" => "حشر بنی نضیر"],
    ["number" => 60, "name" => "الممتحنة", "verses" => 13, "place" => "مدینہ", "theme" => "امتحان اور مومنین"],
    ["number" => 61, "name" => "الصف", "verses" => 14, "place" => "مدینہ", "theme" => "صف اور جہاد"],
    ["number" => 62, "name" => "الجمعة", "verses" => 11, "place" => "مدینہ", "theme" => "جمعہ اور نماز"],
    ["number" => 63, "name" => "المنافقون", "verses" => 11, "place" => "مدینہ", "theme" => "منافقین کی صفات"],
    ["number" => 64, "name" => "التغابن", "verses" => 18, "place" => "مدینہ", "theme" => "تغابن اور قیامت"],
    ["number" => 65, "name" => "الطلاق", "verses" => 12, "place" => "مدینہ", "theme" => "طلاق اور حقوق"],
    ["number" => 66, "name" => "التحريم", "verses" => 12, "place" => "مدینہ", "theme" => "تحریم اور زوجات"],
    ["number" => 67, "name" => "الملك", "verses" => 30, "place" => "مکہ", "theme" => "ملک اور قدرت"],
    ["number" => 68, "name" => "القلم", "verses" => 52, "place" => "مکہ", "theme" => "قلم اور قرآن"],
    ["number" => 69, "name" => "الحاقة", "verses" => 52, "place" => "مکہ", "theme" => "حاقہ اور قیامت"],
    ["number" => 70, "name" => "المعارج", "verses" => 44, "place" => "مکہ", "theme" => "معارج اور سوالات"],
    ["number" => 71, "name" => "نوح", "verses" => 28, "place" => "مکہ", "theme" => "نوح کا قصہ"],
    ["number" => 72, "name" => "الجن", "verses" => 28, "place" => "مکہ", "theme" => "جن اور قرآن"],
    ["number" => 73, "name" => "المزمل", "verses" => 20, "place" => "مکہ", "theme" => "مزمل اور نماز"],
    ["number" => 74, "name" => "المدثر", "verses" => 56, "place" => "مکہ", "theme" => "مدثر اور قرآن"],
    ["number" => 75, "name" => "القيامة", "verses" => 40, "place" => "مکہ", "theme" => "قیامت اور انسان"],
    ["number" => 76, "name" => "الإنسان", "verses" => 31, "place" => "مدینہ", "theme" => "انسان اور نعمتیں"],
    ["number" => 77, "name" => "المرسلات", "verses" => 50, "place" => "مکہ", "theme" => "مرسلات اور قیامت"],
    ["number" => 78, "name" => "النبأ", "verses" => 40, "place" => "مکہ", "theme" => "نبأ اور قیامت"],
    ["number" => 79, "name" => "النازعات", "verses" => 46, "place" => "مکہ", "theme" => "نازعات اور فرشتے"],
    ["number" => 80, "name" => "عبس", "verses" => 42, "place" => "مکہ", "theme" => "عبس اور قرآن"],
    ["number" => 81, "name" => "التكوير", "verses" => 29, "place" => "مکہ", "theme" => "تکویر اور قیامت"],
    ["number" => 82, "name" => "الإنفطار", "verses" => 19, "place" => "مکہ", "theme" => "انفطار اور انسان"],
    ["number" => 83, "name" => "المطففين", "verses" => 36, "place" => "مکہ", "theme" => "مطففین اور حساب"],
    ["number" => 84, "name" => "الإنشقاق", "verses" => 25, "place" => "مکہ", "theme" => "انشقاق اور آسمان"],
    ["number" => 85, "name" => "البروج", "verses" => 22, "place" => "مکہ", "theme" => "بروج اور ایمان"],
    ["number" => 86, "name" => "الطارق", "verses" => 17, "place" => "مکہ", "theme" => "طارق اور نجم"],
    ["number" => 87, "name" => "الأعلى", "verses" => 19, "place" => "مکہ", "theme" => "اعلیٰ اور قرآن"],
    ["number" => 88, "name" => "الغاشية", "verses" => 26, "place" => "مکہ", "theme" => "غاشية اور قیامت"],
    ["number" => 89, "name" => "الفجر", "verses" => 30, "place" => "مکہ", "theme" => "فجر اور قیامت"],
    ["number" => 90, "name" => "البلد", "verses" => 20, "place" => "مکہ", "theme" => "بلد اور انسان"],
    ["number" => 91, "name" => "الشمس", "verses" => 15, "place" => "مکہ", "theme" => "شمس اور قسم"],
    ["number" => 92, "name" => "الليل", "verses" => 21, "place" => "مکہ", "theme" => "لیل اور خیر"],
    ["number" => 93, "name" => "الضحى", "verses" => 11, "place" => "مکہ", "theme" => "ضحیٰ اور رحمت"],
    ["number" => 94, "name" => "الشرح", "verses" => 8, "place" => "مکہ", "theme" => "شرح اور راحت"],
    ["number" => 95, "name" => "التين", "verses" => 8, "place" => "مکہ", "theme" => "تین اور زیتون"],
    ["number" => 96, "name" => "العلق", "verses" => 19, "place" => "مکہ", "theme" => "علق اور انسان"],
    ["number" => 97, "name" => "القدر", "verses" => 5, "place" => "مکہ", "theme" => "قدر اور لیلة القدر"],
    ["number" => 98, "name" => "البينة", "verses" => 8, "place" => "مدینہ", "theme" => "بینة اور اسلام"],
    ["number" => 99, "name" => "الزلزلة", "verses" => 8, "place" => "مدینہ", "theme" => "زلزلہ اور قیامت"],
    ["number" => 100, "name" => "العاديات", "verses" => 11, "place" => "مکہ", "theme" => "عادیات اور انسان"],
    ["number" => 101, "name" => "القارعة", "verses" => 11, "place" => "مکہ", "theme" => "قارعہ اور قیامت"],
    ["number" => 102, "name" => "التكاثر", "verses" => 8, "place" => "مکہ", "theme" => "تکاثر اور انسان"],
    ["number" => 103, "name" => "العصر", "verses" => 3, "place" => "مکہ", "theme" => "عصر اور انسان"],
    ["number" => 104, "name" => "الهمزة", "verses" => 9, "place" => "مکہ", "theme" => "ہمزہ اور انسان"],
    ["number" => 105, "name" => "الفيل", "verses" => 5, "place" => "مکہ", "theme" => "فیل اور ابراہیم"],
    ["number" => 106, "name" => "قريش", "verses" => 4, "place" => "مکہ", "theme" => "قریش اور سفر"],
    ["number" => 107, "name" => "الماعون", "verses" => 7, "place" => "مکہ", "theme" => "ماعون اور انسان"],
    ["number" => 108, "name" => "الكوثر", "verses" => 3, "place" => "مکہ", "theme" => "کوثر اور خیر"],
    ["number" => 109, "name" => "الكافرون", "verses" => 6, "place" => "مکہ", "theme" => "کافرون اور دین"],
    ["number" => 110, "name" => "النصر", "verses" => 3, "place" => "مدینہ", "theme" => "نصر اور اسلام"],
    ["number" => 111, "name" => "المسد", "verses" => 5, "place" => "مکہ", "theme" => "مسد اور ابو لہب"],
    ["number" => 112, "name" => "الإخلاص", "verses" => 4, "place" => "مکہ", "theme" => "اخلاص اور توحید"],
    ["number" => 113, "name" => "الفلق", "verses" => 5, "place" => "مکہ", "theme" => "فلق اور شر"],
    ["number" => 114, "name" => "الناس", "verses" => 6, "place" => "مکہ", "theme" => "ناس اور شیطان"]
];

// Function to generate sample examples for a Surah
function generate_examples($surah) {
    $examples = [];
    // Sample examples based on Surah theme
    switch ($surah['number']) {
        case 1:
            $examples = [
                "بسم اللہ الرحمن الرحیم - اللہ کے نام سے جو بڑا مہربان اور رحم کرنے والا ہے۔",
                "الحمد للہ رب العالمین - سب تعریف اللہ کے لیے جو تمام جہانوں کا پروردگار ہے۔",
                "الرحمن الرحیم - بڑا مہربان اور رحم کرنے والا۔",
                "مالک یوم الدین - حساب کے دن کا مالک۔"
            ];
            break;
        case 2:
            $examples = [
                "ذلک الکتاب لا ریب فیه - یہ وہ کتاب ہے جس میں کوئی شک نہیں۔",
                "الذین یؤمنون بالغیب - وہ لوگ جو غیب پر ایمان رکھتے ہیں۔",
                "و یقیمون الصلاة - اور نماز قائم کرتے ہیں۔",
                "و مما رزقناهم ینفقون - اور جو کچھ ہم نے انہیں دیا ہے اس میں سے خرچ کرتے ہیں۔"
            ];
            break;
        // Add more cases for other Surahs as needed
        default:
            $examples = [
                "بسم اللہ الرحمن الرحیم - اللہ کے نام سے جو بڑا مہربان اور رحم کرنے والا ہے۔",
                "الحمد للہ رب العالمین - سب تعریف اللہ کے لیے جو تمام جہانوں کا پروردگار ہے۔",
                "الرحمن الرحیم - بڑا مہربان اور رحم کرنے والا۔",
                "مالک یوم الدین - حساب کے دن کا مالک۔"
            ];
    }
    return $examples;
}

// Function to generate quiz questions for a Surah
function generate_quiz($surah) {
    $quiz = [];
    // Sample quiz based on Surah info
    $quiz[] = [
        "question" => "سورہ {$surah['name']} کتنی آیات پر مشتمل ہے؟",
        "options" => [$surah['verses'], $surah['verses'] + 1, $surah['verses'] - 1, $surah['verses'] + 2],
        "correct" => 0,
        "explanation" => "سورہ {$surah['name']} {$surah['verses']} آیات پر مشتمل ہے۔"
    ];
    $quiz[] = [
        "question" => "سورہ {$surah['name']} کس مقام پر نازل ہوئی؟",
        "options" => [$surah['place'], "مکہ", "مدینہ", "بیت المقدس"],
        "correct" => 0,
        "explanation" => "سورہ {$surah['name']} {$surah['place']} میں نازل ہوئی۔"
    ];
    $quiz[] = [
        "question" => "سورہ {$surah['name']} کا مرکزی موضوع کیا ہے؟",
        "options" => [$surah['theme'], "ایمان", "نماز", "قیامت"],
        "correct" => 0,
        "explanation" => "سورہ {$surah['name']} کا مرکزی موضوع {$surah['theme']} ہے۔"
    ];
    // Add more generic questions
    $quiz[] = [
        "question" => "سورہ {$surah['name']} قرآن کریم کی کتنی ویں سورہ ہے؟",
        "options" => [$surah['number'], $surah['number'] + 1, $surah['number'] - 1, $surah['number'] + 2],
        "correct" => 0,
        "explanation" => "سورہ {$surah['name']} قرآن کریم کی {$surah['number']} ویں سورہ ہے۔"
    ];
    $quiz[] = [
        "question" => "سورہ {$surah['name']} کا نام کیوں رکھا گیا؟",
        "options" => ["اس کے موضوع کی وجہ سے", "پہلی آیت کی وجہ سے", "آخری آیت کی وجہ سے", "وسط کی آیت کی وجہ سے"],
        "correct" => 0,
        "explanation" => "سورہ {$surah['name']} کا نام اس کے موضوع {$surah['theme']} کی وجہ سے رکھا گیا۔"
    ];
    return $quiz;
}

// Generate lessons for all Surahs
foreach ($surahs as $surah) {
    $lesson = [
        "title" => "سورہ {$surah['name']} - سبق {$surah['number']}",
        "content" => "سورہ {$surah['name']} قرآن کریم کی {$surah['number']} ویں سورہ ہے جو {$surah['place']} میں نازل ہوئی۔ اس سورہ کا مرکزی موضوع {$surah['theme']} ہے اور یہ {$surah['verses']} آیات پر مشتمل ہے۔",
        "examples" => generate_examples($surah),
        "quiz" => generate_quiz($surah)
    ];

    $filename = "lesson_s{$surah['number']}.json";
    file_put_contents($filename, json_encode($lesson, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    echo "Generated {$filename}\n";
}

echo "All Surah lessons generated successfully.\n";
?>
