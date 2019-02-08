<?php

/**
 *  @author Federico Ramón Gasquez
 */
class TelegramApi
{
	/**
	 *
	 * @var string $api_url
	 */
	private $api_url;


	function __construct($token)
	{
		$this->api_url = 'https://api.telegram.org/bot'.$token;
	}


	/**
	 *
	 * @param string $chat_id identifier of chat to send the message
	 * @param string $msg message to send
	 * @param string $parse_mode Send Markdown or HTML, if you want Telegram apps to show bold, italic, fixed-width text or inline URLs in the message.
	 */
	public function sendMessage($chat_id, $msg, $parse_mode = 'Markdown')
	{
		$query = http_build_query([
			'chat_id' => $chat_id,
			'text' => $msg,
			'parse_mode' => $parse_mode,
		]);

		$response =  file_get_contents($this->api_url . '/sendMessage?' . $query);
	}

	/**
	 * 
	 * @param string $chat_id identifier of group to delete a message
	 * @param string $message_id identifier of message to be deleted
	 */
	public function deleteMessage($chat_id, $message_id)
	{
		$query = http_build_query([
			'chat_id' => $chat_id,
			'message_id' => $message_id
		]);

		$response =  file_get_contents($this->api_url . '/deleteMessage?' . $query);
	}

	/**
	 * The bot can only edit its own messages
	 * 
	 * @param string $chat_id
	 * @param string $message_id
	 * @param string $text
	 * 
	 */
	public function editMessageText($chat_id, $message_id, $text)
	{
		$query = http_build_query([
			'chat_id' => $chat_id,
			'message_id' => $message_id,
			'text' => $text
		]);

		$response = file_get_contents($this->api_url . '/editMessageText?' . $query);
	}

	/**
	 *
	 * @param string $chat_id identifier of chat to send the message
	 * @param string $photo_url url of image to send
	 * @param string $caption Photo caption 0-1024 characters
	 * @param string $parse_mode Send Markdown or HTML, if you want Telegram apps to show bold, italic, fixed-width text or inline URLs in the media caption.
	 */
	public function sendPhoto($chat_id, $photo_url, $caption = '', $parse_mode = 'markdown')
	{
		$query = http_build_query([
			'chat_id' => $chat_id,
			'photo' => $photo_url,
			'caption' => $caption,
			'parse_mode' => $parse_mode
		]);
		
		//The bot warns that it is sending a photo
		file_get_contents($this->api_url.'/sendChatAction?chat_id=' . $chat_id . '&action=upload_photo');
		//The bot sends the photo
		$response =  file_get_contents($this->api_url . '/sendPhoto?' . $query);
	}


	/**
	 *
	 * @param string $chat_id identifier of chat to send the message
	 * @param string $audio url of audio to send
	 * @param string $caption Photo caption 0-1024 characters
	 * @param string $parse_mode Send Markdown or HTML, if you want Telegram apps to show bold, italic, fixed-width text or inline URLs in the media caption.
	 */
	public function sendAudio($chat_id, $audio_url, $caption = '', $parse_mode = 'markdown')
	{
		$query = http_build_query([
			'chat_id' => $chat_id,
			'audio' => $audio_url,
			'caption' => $caption,
			'parse_mode' => $parse_mode
		]);

		//The bot warns that it is sending an audio
		file_get_contents($this->api_url.'/sendChatAction?chat_id=' . $chat_id . '&action=upload_audio');
		//The bot sends the audio
		$response =  file_get_contents($this->api_url . '/sendAudio?' . $query);
	}

	/**
	 *
	 * @param string $inline_id identifier of inline query to answer
	 * @param array $elems elements to send
	 */
	public function answerInlineQuery($inline_id, $elems = [])
	{
		$query = http_build_query([
			"inline_query_id" => $inline_id,
			"results" => json_encode($elems),
		]);

		$response = file_get_contents($this->api_url . '/answerInlineQuery?' . $query);
	}

	/**
	 * 
	 * @param string $chat_id identifier of group to ban user
	 * @param string $user_id identifier of the user to be banned
	 * @param string $until_date date where the user will be unbanned
	 * @param array $user_restriction array with restrictions to user
	 */

	public function ban($chat_id, $user_id, $until_date = NULL, $user_restrictions = [])
	{
		$query = http_build_query([
			'chat_id' => $chat_id,
			'user_id' => $user_id,
			'until_date' => $until_date,
			'can_send_messages' => $user_restrictions['can_send_messages'],
			'can_send_media_messages' => $user_restrictions['can_send_media_messanges'],
			'can_send_other_messages' => $user_restrictions['can_send_other_messages'],
			'can_add_web_page_previews' => $user_restrictions['can_add_web_page_previews']
		]);
	  
		$response = file_get_contents($this->api_url . '/restrictChatMember?' . $query);
	}

}