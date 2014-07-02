<?php

Class BlackJackPlayer_Tek2 extends BlackJackPlayer_HiLo
{

     public function shuffle()/*{{{*/
     {
          $this->losecount = 0 ;
     }/*}}}*/

     public function getBet( BlackJackGame $game ) /*{{{*/
     {
          $bet = 5 * ( $this->losecount - 2 );
          $bet = max( 5, $bet );
          BlackJackLog::out( BlackJackLog::BET, "I am betting $bet" );
          return $bet ;
     }/*}}}*/

     private $losecount = 0;

     const WIN       = -1;
     const BLACKJACK = -2 ;
     const LOSE      = 1 ;
     const BUST      = 1;
     const PUSH      = -0.5;

     public function win( )/*{{{*/
     {
          $this->losecount += self::WIN ;
          parent::win();
     }/*}}}*/

     public function blackjack( )/*{{{*/
     {
          $this->losecount += self::BLACKJACK;
          parent::blackjack();
     }/*}}}*/

     public function lose( )/*{{{*/
     {
          $this->losecount += self::LOSE ;
          parent::lose();
     }/*}}}*/

     public function bust( )/*{{{*/
     {
          $this->losecount += self::BUST ;
          parent::bust();
     }/*}}}*/

     public function push(  )/*{{{*/
     {
          $this->losecount += self::PUSH ;
          parent::push();
     }/*}}}*/
}
