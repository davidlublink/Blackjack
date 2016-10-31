<?php

require_once('HiLo.php');

//slightly increase bet on each lose

Class BlackJackPlayer_Tek4 extends BlackJackPlayer
{
     private $lose = 0;

     public function shuffle()/*{{{*/
     {
     }/*}}}*/

     public function getBet( BlackJackGame $game ) /*{{{*/
     {
          return 5 + min( 95, $this->lose * 3 ) ;
     }/*}}}*/

     public function getTrueCount($game) { return 0 ; }

     public function win( )/*{{{*/
     {
          $this->lose = 0;
          parent::win();
     }/*}}}*/

     public function blackjack( )/*{{{*/
     {
          $this->lose = 0;
          parent::blackjack();
     }/*}}}*/

     public function lose( )/*{{{*/
     {
          $this->lose++;
          parent::lose();
     }/*}}}*/

     public function bust( )/*{{{*/
     {
          $this->lose++;
          parent::bust();
     }/*}}}*/

     public function push(  )/*{{{*/
     {
          parent::push();
     }/*}}}*/
}
