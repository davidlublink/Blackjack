<?php

require_once( 'HiLo.php' );


# Source : http://en.wikipedia.org/wiki/Card_counting#Systems
Class BlackJackPlayer_HiLoOpt2 extends BlackJackPlayer_HiLo
{

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