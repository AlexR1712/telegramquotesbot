<?php 

require 'vendor/autoload.php';

$bot   = new AlexR1712\Telegram();

$update = json_decode(file_get_contents("php://input"), true);

if (isset($update['message']['text'])) {

    $text = $update['message']['text'];
    $chat_id = $update['message']['chat']['id'];
    $first_name = $update['message']['chat']['first_name'];
    $last_name = (isset($update['message']['chat']['last_name'])) ? $update['message']['chat']['last_name'] : "";
    $username = (isset($update['message']['chat']['username'])) ? $update['message']['chat']['username'] : "";

    if (strpos($text, '/start') === 0) {
        
        $message = "Hola *$first_name*!, soy un bot de Frases presentado en el *II CodingNEXT* por @AlexR1712";
        $bot->sendMessage($chat_id, $message, ['parse_mode' => 'Markdown']);
        $message = "Usarme es sencillo, solo enviame el comando /quote ";
        $bot->sendMessage($chat_id, $message, ['parse_mode' => 'Markdown']);

    } elseif (strpos($text, '/quote') === 0 ) {

        $quotes = new AlexR1712\Forismatic();
        $quote = $quotes->getQuote()['quoteText'];
        $author = $quotes->getQuote()['quoteAuthor'];
        $message = "🖊 `$quote` \n ~ $author";

        $bot->sendMessage($chat_id, $message,['parse_mode' => 'Markdown']);
    } else {
        $message = "Oye $first_name 😅, espera aun no aprendo tan rapido, que te parece si me ayudas con mi [codigo fuente](https://github.com/AlexR1712/telegramquotesbot)?";
        $bot->sendMessage($chat_id, $message, ['parse_mode' => 'Markdown']);
    }

}


