<?php


# Source : http://en.wikipedia.org/wiki/Card_counting#Systems
Class BlackJackPlayer_Martingale extends BlackJackPlayer
{

     public function shuffle()/*{{{*/
     {
          $this->bet = 0 ;
     }/*}}}*/

     public function getBet( BlackJackGame $game ) /*{{{*/
     {
          $bet = max ( 5, $this->bet );
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
          $this->bet = 5; 
          parent::win();
     }/*}}}*/

     public function blackjack( )/*{{{*/
     {
          $this->bet /= 2;
          parent::blackjack();
     }/*}}}*/

     public function lose( )/*{{{*/
     {
          $this->bet *= 2;
          parent::lose();
     }/*}}}*/

     public function bust( )/*{{{*/
     {
          $this->bet *= 2;
          parent::bust();
     }/*}}}*/

     public function push(  )/*{{{*/
     {
          parent::push();
     }/*}}}*/
}
