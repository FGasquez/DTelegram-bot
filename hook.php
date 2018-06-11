<?php

	require 'classes/TelegramApi.php';
	$token = '';

	$api = new TelegramApi($token);

	$json = file_get_contents("php://input");
	$request = json_decode($json, $assoc=false);

 	if(isset($request->message) && ($request->message->text == '/test'))
 	{
		$chat_id = $request->message->from->id;

		$api->sendMessage($chat_id, 'OK!');

		$image = 'https://upload.wikimedia.org/wikipedia/commons/thumb/8/82/Telegram_logo.svg/2000px-Telegram_logo.svg.png';
		$api->sendPhoto($chat_id, $image);

		$audio = 'https://www.drogui.ml/sounds/fideos.mp3';
		$api->sendAudio($chat_id, $audio);
	}
	
	if(isset($request->inline_query))
	{
		$query_id = $request->inline_query->id;	
		$inline_test = 
			[
				[
					'type' => 'article',
					'id' => '1',
					'title' => 'test1',
					'input_message_content' => [
						'message_text' => 'Inline Test 1',
					],
				],
				[
					'type' => 'article',
					'id' => '2',
					'title' => 'test2',
					'input_message_content' => [
						'message_text' => 'Inline Test 2',
					],
				],
			];

		$api->answerInlineQuery($query_id, $inline_test);
	}