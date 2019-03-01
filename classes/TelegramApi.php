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
	private $token;


	function __construct($token)
	{
		$this->token = $token;
		$this->api_url = 'https://api.telegram.org/bot'.$token.'/';
	}


	public function execute($method, $params = [])
	{
		$query = http_build_query($params);
		return file_get_contents($this->api_url . $method . '?' . $query);
	}

	/**
	 *
	 * @param string $chat_id identifier of chat to send the message
	 * @param string $text message to send
	 * @param array $options Send the extra options from telegram bot api (parsemode, reply_markup, reply_to_message_id, etc).
	 */
	public function sendMessage($chat_id, $text, $options = [])
	{
		$q = array_merge([
			'chat_id' => $chat_id,
			'text' => $text,
		], $options);

		$this->execute('sendMessage', $q);
	}

	/**
	 * 
	 * @param string $chat_id identifier of group to delete a message
	 * @param string $message_id identifier of message to be deleted
	 */
	public function deleteMessage($chat_id, $message_id)
	{
		$q = [
			'chat_id' => $chat_id,
			'message_id' => $message_id
		];

		$this->execute('deleteMessage'.$q);
	}

	/**
	 * The bot can only edit its own messages
	 * 
	 * @param string $chat_id
	 * @param string $message_id
	 * @param string $text
	 * 
	 */
	public function editMessageText($chat_id, $message_id, $text, $options)
	{
		$q = array_merge([
			'chat_id' => $chat_id,
			'message_id' => $message_id,
			'text' => $text
		], $options);

		$this->execute('editMessageText', $q);
	}

	/**
	 *
	 * @param string $chat_id identifier of chat to send the message
	 * @param string $photo_url url of image to send
	 * @param array  $options send the extra options from telegram bot api (parsemode, caption, reply_to_message_id, etc).
	 */
	public function sendPhoto($chat_id, $photo_url, $caption = '', $options = [])
	{
		$q = array_merge([
			'chat_id' => $chat_id,
			'photo' => $photo_url,
			'caption' => $caption
		], $options);
		
		//The bot warns that it is sending a photo
		$this->sendChatAction($chat_id, 'upload_photo');

		$this->execute('sendPhoto', $q);
	}

	/**
	 *
	 * @param string $chat_id identifier of chat to send the message
	 * @param string $video_url url of image to send
	 * @param array  $options send the extra options from telegram bot api (parsemode, caption, reply_to_message_id, etc).
 	 */
	public function sendVideo($chat_id, $video_url, $caption = '', $options = [])
	{
		$q = array_merge([
			'chat_id' => $chat_id,
			'video' => $video_url,
			'caption' => $caption
		], $options);
		
		//The bot warns that it is sending a photo
		$this->sendChatAction($chat_id, 'upload_video');

		$this->execute('sendVideo', $q);
	}

	/**
	 * 
	 * @param string $chat_id identifier of chat to send the message
	 * @param string $sticker file_id or pass an HTTP URL as a String for Telegram to get a .webp file from the Internet
	 */
	function sendSticker($chat_id, $sticker, $options = [])
	{
		$q = array_merge([
			'chat_id' => $chat_id,
			'sticker' => $sticker
		], $options);

		$this->execute('sendSticker', $q);
	}


	/**
	 *
	 * @param string $chat_id identifier of chat to send the message
	 * @param string $audio url of audio to send
	 * @param array  $options send the extra options from telegram bot api (parsemode, caption, reply_to_message_id, etc).
	 */
	public function sendAudio($chat_id, $audio_url, $caption = '', $options = [])
	{
		$q = array_merge([
			'chat_id' => $chat_id,
			'audio' => $audio_url,
			'caption' => $caption
		], $options);

		//The bot warns that it is sending an audio
		$this->sendChatAction($chat_id, 'upload_audio');

		$this->execute('sendAudio', $q);
	}

	/**
	 *
	 * @param string $chat_id identifier of chat to send the message
	 * @param string $audio url of audio to send
	 * @param array  $options send the extra options from telegram bot api (parsemode, caption, reply_to_message_id, etc).
	 */
	public function sendDocument($chat_id, $doc_url, $options = [])
	{
		$q = array_merge([
			'chat_id' => $chat_id,
			'document' => $doc_url
		], $options);

		//The bot warns that it is sending an audio
		$this->sendChatAction($chat_id, 'upload_document');

		$this->execute('sendDocument', $q);
	}

	/**
	 * 
	 * @param string $chat_id identifier of chat to send the message
	 * @param string $action 
	 */
	public function sendChatAction($chat_id, $action)
	{
		$q = [
			'chat_id' => $chat_id,
			'action' => $action
		];
		$this->execute('sendChatAction', $q);
	}

	/**
	 *
	 * @param string $inline_id identifier of inline query to answer
	 * @param array $elems elements to send
	 */
	public function answerInlineQuery($inline_id, $elems = [])
	{
		$q = [
			"inline_query_id" => $inline_id,
			"results" => json_encode($elems),
		];

		//$response = file_get_contents($this->api_url . 'answerInlineQuery?' . $query);
		$this->execute('answerInlineQuery', $q);
	}

	/**
	 * 
	 * @param string $chat_id identifier of group to ban user
	 * @param string $user_id identifier of the user to be banned
	 * @param string $until_date date where the user will be unbanned
	 * @param array $user_restriction array with restrictions to user
	 */

	public function ban($chat_id, $user_id, $options = [])
	{

		$q = array_merge([
			'chat_id' => $chat_id,
			'user_id' => $user_id
		], $options);

		$this->execute('restrictChatMember', $q);
	}

	/**
	 * 
	 * @param string $name identifier of sticker set
	 */
	public function getStickerSet($name)
	{
		//$response = file_get_contents($this->api_url . 'getStickerSet?name=' . $name);
		return json_decode($this->execute('getStickerSet?name='.$name), true);
	}

	public function getMe()
	{
		return json_decode($this->execute('getMe'), False);
	}

	/**
	 * 
	 * @param string $chat_id the identifier of the chat that will receive the message
	 * @param string $from_chat_id the identifier of the chat from which the message is forwarded 
	 * @param string $message_id the identifier of message to forward
	 */
	public function forwardMessage($chat_id, $from_chat_id, $message_id)
	{
		$q = [
			'chat_id' => $chat_id,
			'from_chat_id' => $from_chat_id,
			'message_id' => $message_id
		];

		$this->execute('forwardMessage' . $q);
	}

}