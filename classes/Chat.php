<?php

/**
 *  @author Federico Ramón Gasquez
 */
class Chat
{
        private $api;
        private $message;
        private $inlineQuery;
        private $sticker;
        private $type;
        private $forward;
        private $callbackData = false;
        
        function __construct($api)
        {
            $this->api = $api;
            
            $json = file_get_contents("php://input");
            $request = json_decode($json, $assoc=false);
            $this->message = $request->message;
            $this->inlineQuery = (!isset($request->inline_query))? False : $request->inline_query; 
            $this->sticker = (!isset($request->message->sticker))? False : $request->message->sticker;
            $this->forward =(!isset($request->message->forward_from))? False: $request->message->forward_from;
            if(isset($request->callback_query)){
                $this->callbackData = $request->callback_query->data;
                $this->message = $request->callback_query->message;
            }
        }

        function isCallback()
        {
            return ($this->callbackData != False);
        }

        function isInline()
        {
            return ($this->inlineQuery != False);
        }

        function isGroup()
        {
            return ($this->getType() == 'group');
        }

        function isSuperGroup()
        {
            return ($this->getType() == 'supergroup');
        }
        
        function isChannel()
        {
            return ($this->getType() == 'channel');
        }

        function isPrivate()
        {
            return ($this->getType() == 'private');
        }

        function isForwarded()
        {
            return ($this->forward != false);
        }

        function isFromUsername($user)
        {
            return ($this->getUsername() == $user);
        }

        function isFromSenderId($user_id)
        {
            return ($this->getSenderId() == $user_id);
        }

        function isFromChat($chat_id)
        {
            return ($this->getChatId() == $chat_id);
        }

        function containSticker()
        {
            return ($this->sticker != false);
        }

        function getText()
        {
            return $this->message->text;
        }

        function getChatId()
        {
            return $this->message->chat->id;
        }

        function getMessageId()
        {
            return $this->message->message_id;
        }

        function getSenderId()
        {
            return $this->message->from->id;
        }

        function getUsername()
        {
            return $this->message->from->username;
        }

        function getFirstname()
        {
            return $this->message->from->first_name;
        }

        function getLastname()
        {
            return $this->message->from->last_name;
        }

        function getType()
        {
            return $this->message->chat->type;
        }

        function getStickerId()
        {
            if($this->containSticker())
            {
                return($this->sticker->file_id);
            }
        }

        function getStickerName()
        {
            if($this->containSticker())
            {
                return $this->sticker->set_name;
            }
        }

        function getInlineId()
        {
            if($this->isInline())
            {
                return $this->inlineQuery->id;
            }

            return -1;
        }

        function getForwardId()
        {
            if($this->isForwarded())
            {
                return $this->forward->id;
            }
        }

        function getForwardUsername()
        {
            return $this->forward->username;
        }

        function getCallbackData()
        {
            return $this->callbackData;
        }

        function evalCommand($c)
        {
            $text = ($this->isCallback()) ? $this->getCallbackData() : $this->getText();
            return (($text == $c) || (preg_match($c,$text)));
        }

        function remove()
        {
            $this->api->deleteMessage($this->getChatId(), $this->getMessageId());
        }

        function reply($text, $options = [])
        {
            $this->api->sendMessage($this->getChatId(), $text, $options);
        }

        function replyPhoto($imageUrl, $caption, $options = [])
        {
            $this->api->sendPhoto($this->getChatId(), $imageUrl, $caption, $options);
        }

        function replyAudio($audioUrl, $caption = '', $options = [])
        {
            $this->api->sendAudio($this->getChatId(), $audioUrl, $caption, $options);
        }

        function replyVideo($video_url, $caption = '', $options = [])
        {
            $this->api->sendVideo($this->getChatId(), $video_url, $caption, $options);
        }

        function replySticker($sticker,  $options = [])
        {
            $this->api->sendSticker($this->getChatId(), $sticker, $options);
        }

        function forwardMessageTo($chat_id)
        {
            $this->api->forwardMessage($chat_id, $this->getChatId(), $this->getMessageId());
        }

        //this method only works when contains a callbackData
        function editMessage($text, $options = [])
        {
            if($this->isCallback()){
                $this->api->editMessageText($this->getChatId(), $this->getMessageId(), $text, $options);
            }
        }
        
        function inlineReply($r)
        {
            $this->api->answerInlineQuery($this->getChatId(), $r);
        }
}