<?php

	require 'classes/TelegramApi.php';
	$token = '';

	$api = new TelegramApi($token);

	$json = file_get_contents("php://input");
	$request = json_decode($json, $assoc=false);

	$chat_id = $request->message->from->id;
	$msg = $request->message->text;

	$api->sendMessage($chat_id, 'OK!');

	$image = 'https://upload.wikimedia.org/wikipedia/commons/thumb/8/82/Telegram_logo.svg/2000px-Telegram_logo.svg.png';
	$api->sendPhoto($chat_id, $image);

	$audio = 'https://www.drogui.ml/sounds/fideos.mp3';
	$api->sendAudio($chat_id, $audio);