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
	* @param string $chat_id identification of chat to send the message
	* @param string $msg message to send
	* @param string $parse_mode 
	*/
	function sendMessage($chat_id, $msg, $parse_mode = 'Markdown')
	{
		$query = http_build_query([
			'chat_id' => $chat_id,
			'text' => $msg,
			'parse_mode' => $parse_mode,
		]);

		$response =  file_get_contents($this->api_url . '/sendMessage?' . $query);
		return json_decode($response, false);
	}


	/**
	*
	* @param string $chat_id identification of chat to send the message
	* @param string $photo_url url of image to send
	*/
	function sendPhoto($chat_id, $photo_url)
	{
		$query = http_build_query([
			'chat_id' => $chat_id,
			'photo' => $photo_url,
		]);

		$response =  file_get_contents($this->api_url . '/sendPhoto?' . $query);
		return json_decode($response, false);
	}


	/**
	*
	* @param string $chat_id identification of chat to send the message
	* @param string $audio url of audio to send
	*/
	function sendAudio($chat_id, $audio_url)
	{
		$query = http_build_query([
			'chat_id' => $chat_id,
			'audio' => $audio_url,
		]);

		$response =  file_get_contents($this->api_url . '/sendAudio?' . $query);
		return json_decode($response, false);
	}


}