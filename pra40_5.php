<?php

//じゃんけん

const STONE = 1;
const SCISSORS = 2;
const PAPER = 3;
const EQUAL = 0;
const LOSE = 1;
const WIN = 2;
const RETRY = 1;
const END = 9;


const HAND_TYPE = array(
    STONE => 'グー',
    SCISSORS => 'チョキ',
    PAPER => 'パー',
);

const RESULT_TYPE = array(
    EQUAL => 'あいこ',
    LOSE => '負け',
    WIN => '勝ち',
);

const RETRY_TYPE = array(
    RETRY => 'もう一度する',
    END => '終了する',
);


function getMyHand(){
    echo 'じゃんけん' . PHP_EOL . STONE . " → " . HAND_TYPE[STONE] . "、" . SCISSORS . " → " . HAND_TYPE[SCISSORS] . "、" . PAPER . " → " . HAND_TYPE[PAPER] . PHP_EOL;
    $player_hand = trim(fgets(STDIN));
    $player_hand_check = checkHand($player_hand);
    if(!$player_hand_check){
        return getMyHand();
    }
    return $player_hand;
}

function checkHand($hand){
    if(empty($hand)){
        return false;
    }
    if(!($hand == STONE || $hand == SCISSORS || $hand == PAPER)){
        return false;
    }
    return true;
}

function getCpHand(){
    $cp_hand = random_int(1,3);
    return $cp_hand;
}

function result($player_hand ,$cp_hand ){
    $result = ($player_hand - $cp_hand + 3) % 3 ;
    return $result;
}

function show($result){
    switch($result){
        case EQUAL :
            echo RESULT_TYPE[EQUAL] . PHP_EOL;
            break;
        case LOSE :
            echo RESULT_TYPE[LOSE] . PHP_EOL;
            break;
        case WIN :
            echo RESULT_TYPE[WIN] . PHP_EOL;
            break;
    }
}

function checkRetry($hand){
    if(empty($hand)){
        return false;
    }
    if(!is_numeric($hand)){
        return false;
    }
    switch($hand){
        case RETRY :
            return true;
        case END :
            return true;
        default :
            return false;
    }
}

function retry(){
    echo 'もう一度やりますか？' . PHP_EOL . RETRY . '→' . RETRY_TYPE[RETRY] . '、' . END . '→' . RETRY_TYPE[END] . PHP_EOL ;
    $type = trim(fgets(STDIN));
    
    $type_check = checkRetry($type);
    if($type_check){
        switch($type){
            case RETRY :
                return true;
            case END :
                return false;
        }
    }else{
        return retry();
    }
}

function jankenStart(){
    $player_hand = getMyHand();
    $cp_hand = getCpHand();
    $result = result($player_hand ,$cp_hand );
    echo "ぽん！！" . PHP_EOL;
    echo "player : " . HAND_TYPE[$player_hand] . PHP_EOL;
    echo "cp : " . HAND_TYPE[$cp_hand] . PHP_EOL;
    show($result);
    //あいこの場合最初から
    if($result === 0){
        return jankenStart();
    }
    $retry = retry();
    if($retry){
        return jankenStart();
    }
    echo "ゲームを終了します。" . PHP_EOL;
}

jankenStart();
