<?php

/*
40 じゃんけんを作成しよう！
下記の要件を満たす「じゃんけんプログラム」を開発してください。
要件定義
・使用可能な手はグー、チョキ、パー
・勝ち負けは、通常のじゃんけん
・PHPファイルの実行はコマンドラインから。
ご自身が自由に設計して、プログラムを書いてみましょう！


20200406
まず、関数名のつづりが気になります。
JyankenStert
こちらですが、JyankenStart の方が正しいですか？
またローマ字ですと、Jyanken ではなく、 Jankenが正しいと思います。

機能ごとに関数にしようとされたのは、大変素晴らしいと思います。
 $player_hand = check(JyankenPon());
 
check関数から、自分の手を取得するのは、不自然かなと思います。
バリデーション用の関数は、あくまでバリデーションをするだけにしましょう。
戻り値はbooleanにしましょう。

バリデーションの中で、再帰関数を書いてはいけません。
JyankenPon関数で自分の手を取得するのであれば、この関数内でバリデーションを呼び、
バリデーションの結果がNGなら再帰関数するような処理にしてください。

関数名ですが、動詞を意識した関数名の方がベターかもしれません。
JyankenPon　よりは、getMyHand や inputMyHand,
Cphand よりは、getCpHand （※hand もつづりミスですね）
の方が自然かなと思います。

Cphand関数の、rand関数ですが、
現場ではあまり推奨されておりません。
理由としては、書き方が古く、動作が遅いからです。
PHPには同じ処理でもうひとつ関数が用意されていますので、そちらを使用してみてください。

じゃんけんのアルゴリズムですが、
せっかくなので、こちらのロジックを使用してみましょう。
https://qiita.com/mpyw/items/3ffaac0f1b4a7713c869
(自分の手 - 相手の手 + 3) % 3
これを使用すると、勝敗が0,1,2 で取得できます。

Result関数の中で結果を表示するのではなく、
別にshow 関数を作成し、その中で結果を表示してみましょうか。
Result　というよりは、判定を意味する関数名の方がよりわかりやすいかもしれません。（resultなど）
関数名ですが、イニシャルが大文字のものや小文字のものがまざっているので、統一いたしましょう。
一般的には小文字から始まるかと思いますので、小文字にいたしましょうか。

Retry関数ですが、
期待値が入力されないばあいは、再帰関数にいたしましょう。
また、1 が入力された場合に、JyankenStert を呼ばれておりますが、
再帰関数は再帰する関数内で書いた方がよろしいので、
Retry関数内で再帰するのではなく、JyankenStert関数内で再帰するようにしてみてください。
このあたり修正してみてください＾＾


20200409
checkHand関数のバリデーションですが、
現状、1,2,3 以外の数字を入力してもバリデーションチェック
にひっかからないように思うので、
チェックを追加してください。

ゲームが終了する際は、何かしらメッセージを出力してみましょうか。

あとは、せっかくですので、定数を使用してみましょう。
const STONE = 0;
//省略
const HAND_TYPE = array(
    STONE => 'グー',
    //省略
);
のように定数を関数外に宣言してあげることで、
function show($result){
    switch($result){
        case 0 :
        
このあたりを定数で書けるかと思います。
定数を使用すると、もし仕様の変更があっても、
プログラムの修正箇所が少なくすみます。
勝敗やリトライに関しても定数を使用して書いてみましょう。
このあたり、修正してみてください＾＾

修正箇所
・checkHand関数のis_numericから1,2,3,のチェックに変更
・jankenStart関数の最後に"ゲームを終了します。" 
・定数の作成、各場所に配置
・ジャンケンの手を出力

20200413
いい感じですね！
見違えるほど、コードがキレイになってきました。

$show = show($result);
show関数は、単に結果を出力するためだけの関数にしたいので、
返り値は不要かと思います。
if($show === 0){
    return jankenStart();
}
$show という変数名も不自然に感じますが、
ここは、result関数の返り値を使用してください。

また　0 という数字は定数を使用しましょうか。

show関数内のswitchで使用している0,1,2 も定数にしてみてください。
$judge という変数名でもよろしいですが、$result の方がより自然かなと思います。
retry 関数の1, 9 も定数にいたしましょう。
checkRetry　も同様です。
このあたり修正できればOKかと思います！
よろしければ、修正してみてください＾＾

修正箇所
・$show = show($result);　を　show($result);　に修正しました。
・定数の設定を4,5,6,7,8を追加し、各箇所修正しまいた。
・$judge を $result に修正しまいた。


*/

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