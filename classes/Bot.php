<?php

	require 'classes/TelegramApi.php';
	require 'classes/Chat.php';

/**
 *  @author Federico Ramón Gasquez
 */
class Bot
{
    private $token;
    private $api;
    private $chat;
    private $commandFound = false;
    private $ended = false;

    function __construct($token)
    {
        $this->token = $token;
        $this->api = new TelegramApi($token);
        $this->chat = new Chat($this->api);

    }

    function getChat()
    {
        return $this->chat;
    }

    function getApi()
    {
        return $this->api;
    }

    function bus($command, $f)
    {
        if(!$this->ended){
            
            if($this->chat->evalCommand($command))
            {
                $this->commandFound = true;
                $f($this->chat, $this->api);
            }
        }
    }

    function do ($f, $silent = true)
    {
        if(!$this->ended){
            $f($this->chat, $this->api);
            ($silent)?:$this->commandFound = true;
        }
    }

    function inlineReply($iReply)
    {
        if($this->chat->isInline()){
            $this->chat->inlineReply($iReply);
        }
    }

    function close($f)
    {
        if(!$this->commandFound)
        {
            $f($this->chat, $this->api);
        }
        $this->ended = True;
    }
}