<?php
session_start();

header('Content-Type: text/plain');

function convertToRankMathFAQ($text)
{
    $lines = explode("\n", $text);
    $questions = [];
    $currentQuestion = null;
    $currentAnswer = '';

    $hasQandA = preg_match('/^(Q:|A:)/m', $text);

    foreach ($lines as $line) {
        if ($hasQandA) {
            if (preg_match('/^Q: (.+)/', $line, $matches)) {
                if ($currentQuestion !== null) {
                    $questions[] = [
                        'title' => $currentQuestion,
                        'content' => $currentAnswer
                    ];
                }
                $currentQuestion = $matches[1];
                $currentAnswer = '';
            } elseif (preg_match('/^A: (.+)/', $line, $matches)) {
                $currentAnswer .= $matches[1];
            }
        } else {
            if (empty(trim($line))) continue;
            
            if ($currentQuestion === null) {
                $currentQuestion = $line;
            } else {
                $currentAnswer = $line;
                $questions[] = [
                    'title' => $currentQuestion,
                    'content' => $currentAnswer
                ];
                $currentQuestion = null;
                $currentAnswer = '';
            }
        }
    }

    if ($currentQuestion !== null && !$hasQandA) {
        $questions[] = [
            'title' => $currentQuestion,
            'content' => $currentAnswer
        ];
    }
    
    $faqItems = [];
    $faqJson = [];

    foreach ($questions as $index => $question) {
        if (!isset($_SESSION['id'])) {
            $_SESSION['id'] = 0;
        } else {
            $_SESSION['id'] += 10;
        }
        $id = "faq-question-" . $_SESSION['id'];
        $faqItems[] = "<div class=\"rank-math-faq-item\"><h3 class=\"rank-math-question\">{$question['title']}</h3><div class=\"rank-math-answer\">{$question['content']}</div></div>";
        $faqJson[] = [
            'id' => $id,
            'title' => $question['title'],
            'content' => $question['content'],
            'visible' => true
        ];
    }

    $json = json_encode(['questions' => $faqJson, 'className' => 'wp-block-rank-math-faq-block']);

    return "<!-- wp:rank-math/faq-block {$json} -->\n<div class=\"wp-block-rank-math-faq-block\">" . implode('', $faqItems) . "</div>\n<!-- /wp:rank-math/faq-block -->";
}

if (isset($_POST['inputText'])) {
    $inputText = $_POST['inputText'];
    $outputText = convertToRankMathFAQ($inputText);
    echo $outputText;
} else {
    http_response_code(400);
    echo 'Invalid request';
}