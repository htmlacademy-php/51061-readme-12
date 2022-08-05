<?php

use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;

require 'vendor/autoload.php';
// Конфигурация траспорта
$dsn = $_ENV['DSN'];
$transport = Transport::fromDsn($dsn);
// Отправка сообщения
$mailer = new Mailer($transport);

$send_email = function ($message_info) use ($mailer) {
    // Формирование сообщения
    $message = new Email();
    $message->from($_ENV["SMTP_LOGIN"]);
    $message->to($message_info['to']);
    $message->subject($message_info['subject']);
    $message->text($message_info['text']);
    $mailer->send($message);
};


