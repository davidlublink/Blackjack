<?php

require_once('HiLo.php');

Class BlackJackPlayer_Tek3 extends BlackJackPlayer
{
     private $win = 0;

     public function shuffle()/*{{{*/
     {
     }/*}}}*/

     public function getBet( BlackJackGame $game ) /*{{{*/
     {
          return 5 + min( 95, $this->win * 3 ) ;
     }/*}}}*/

     public function getTrueCount($game) { return 0 ; }

     public function win( )/*{{{*/
     {
          $this->win++;
          parent::win();
     }/*}}}*/

     public function blackjack( )/*{{{*/
     {
          $this->win++;
          parent::blackjack();
     }/*}}}*/

     public function lose( )/*{{{*/
     {
          $this->win = 0 ;
          parent::lose();
     }/*}}}*/

     public function bust( )/*{{{*/
     {
          $this->win = 0;
          parent::bust();
     }/*}}}*/

     public function push(  )/*{{{*/
     {
          $this->win = 0;
          parent::push();
     }/*}}}*/
}
