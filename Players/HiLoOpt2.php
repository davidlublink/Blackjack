<?php

require_once( 'Player.php' );


# Source : http://en.wikipedia.org/wiki/Card_counting#Systems
Class BlackJackPlayer_HiLoOpt2 extends BlackJackPlayer
{

     public function getBet( BlackJackGame $game ) /*{{{*/
     {
          if ( $this->count < -5 ) throw new exception("Player left the table because of bad count!");

          $bet = max ( 10, round ($this->count / 8 * 5  ) ); 
          echo "I am betting $bet$\n";

          return $bet ;
     }/*}}}*/

     private $count = 0 ;

     private static $countingSystem = array(/*{{{*/
               '2' => 1 ,
               '3' => 1 ,
               '4' => 2 ,
               '5' => 2 ,
               '6' => 1 ,
               '7' => 1,
               '8' => 0,
               '9' => 0,
               '10' => -2 ,
               'J' => -2 ,
               'Q' => -2 ,
               'K' => -2 ,
               'A' => 0 ,
               );/*}}}*/

     public function revealcard( $card )/*{{{*/
     {
          echo "Player : Counting $card {$this->count} \n";
          $this->count += self::$countingSystem[ $card ] ;
     }/*}}}*/

     public function shuffle()/*{{{*/
     {
          echo "Resetting count because of shuffle!\n";
          $this->count = 0 ;
     }/*}}}*/


}
