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
    private $commandExecuted = false;
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

    function do ($f)
    {
        if(!$this->ended){
            $this->commandExecuted = (($f($this->chat, $this->api)) === False && !$this->commandExecuted) ? False : True;
        }
    }


    function bus($command, $f)
    {            
            if($this->chat->evalCommand($command))
            {
                $this->do($f);
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
        if(!$this->commandExecuted)
        {
            $f($this->chat, $this->api);
        }
        $this->ended = True;
    }
}