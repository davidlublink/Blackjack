<?php

require_once( 'HiLo.php' );

# Source : http://en.wikipedia.org/wiki/Card_counting#Systems
Class BlackJackPlayer_Tek extends BlackJackPlayer_HiLo
{

     private static $countingSystem = array(/*{{{*/
               '2' => 1 ,
               '3' => 1 ,
               '4' => 1 ,
               '5' => 1 ,
               '6' => 1 ,
               '7' => 1,
               '8' => 0,
               '9' => 0,
               '10' => -1 ,
               'J' => -1 ,
               'Q' => -1 ,
               'K' => -1 ,
               'A' => -1 ,
               );/*}}}*/

     public function shuffle()/*{{{*/
     {
          $this->bet = 0 ;
     }/*}}}*/

     public function getBet( BlackJackGame $game ) /*{{{*/
     {
          $bet = max ( 5, 10 + $this->bet );
          BlackJackLog::out( BlackJackLog::BET, "I am betting $bet" );
          return $bet ;
     }/*}}}*/

     private $bet = 0;

     const WIN       = -2 ;
     const BLACKJACK = -5 ;
     const LOSE      = 5  ;
     const BUST      = 6  ;

     public function win( )/*{{{*/
     {
          $this->bet += self::WIN ;
          parent::win();
     }/*}}}*/

     public function blackjack( )/*{{{*/
     {
          $this->bet += self::BLACKJACK;
          parent::blackjack();
     }/*}}}*/

     public function lose( )/*{{{*/
     {
          $this->bet += self::LOSE ;
          parent::lose();
     }/*}}}*/

     public function bust( )/*{{{*/
     {
          $this->bet += self::BUST ;
          parent::bust();
     }/*}}}*/

     public function push(  )/*{{{*/
     {
          parent::push();
     }/*}}}*/
}
