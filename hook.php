<?php

    require 'classes/Bot.php';

    $token = '';

    $bot = new Bot($token);


    function test($c, $a){
        
        $c->reply('test 1',[
            'parsemode' => 'html',
            'reply_to_message_id' => $c->getMessageId()
            ]);


        $image = 'https://upload.wikimedia.org/wikipedia/commons/thumb/8/82/Telegram_logo.svg/2000px-Telegram_logo.svg.png';
        $c->replyPhoto($image, 'Image Test');

        $audio = 'https://www.drogui.ml/sounds/fideos.mp3';
        $c->replyAudio($audio, 'Audio test');

    }

// Example of use silent functions
    $bot->bus('/t1', function ($c, $a){
        $c->reply('Test silent');
        return False;
    });

    $bot->bus('/t2', function($c, $a){
        $c->reply('test forcing true');
        return true;
    });

    $bot->bus('/t3', function($c, $a){
        $c->reply('test lazy');
    });
// ------------------------------------
    $bot->bus('/test', test);
    
    //return ID and Usermane from sender
    $bot->bus('/myinfo', function($c, $a){
        if($c->isPrivate()){
            $c->reply(
                "---- user info ----".
                "\nUser id: " . $c->getSenderId().
                "\nUsername: ". $c->getUsername());
        }
    });

    //if receive a sticker, return a random sticker from same sitckerSet
    $bot->do(function($c, $a){
        if($c->isPrivate() && $c->containSticker()){
            $c->reply('StickerSet name: ' . $c->getStickerName());
			$stickers = $a->getStickerSet($c->getStickerName());
            $rSticker = array_rand($stickers['result']['stickers']);
            $c->replySticker($stickers['result']['stickers'][$rSticker]['file_id']);
        }
        return false;
    });


    $bot->bus('/testKeyboard', function($c, $a){
        $keyboard = [
                    'inline_keyboard' => [
                        [
                            [
                                'text' => 'Google',
                                'url' => 'http://google.com/'
                            ],
                            [
                                'text' => 'btn2',
                                'callback_data' => '/t2'
                            ],
                        ]
                    ],
                    'one_time_keyboard' => True
                ];
        $c->reply('Test Keyboard', ['reply_markup' => json_encode($keyboard)]);

    });


    $bot->bus('/t2', function($c, $a){
        if($c->isCallback()){
            //delete keyboard
            $c->editMessage($c->getText(), ['reply_markup' => Null]);
            $c->reply('Button pressed');
        }else{
            $c->reply('Nothing to do here...');
        }
    });

    $bot->close(function($c, $a){
        if($c->isPrivate()){
            $c->reply('Command not found');
        }
    });
    
    
    
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
    
    //$api->answerInlineQuery($query_id, $inline_test);
    $bot->inlineReply($inline_test);
    
    $bot->bus('something', function ($chat, $t_api){
        $chat->reply('Something');
    });
    