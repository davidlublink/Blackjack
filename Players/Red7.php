<?php

require_once( 'HiLo.php' );

# Source : http://en.wikipedia.org/wiki/Card_counting#Systems
Class BlackJackPlayer_Red7 extends BlackJackPlayer_HiLo
{

     private static $countingSystem = array(/*{{{*/
               '2' => 1 ,
               '3' => 1 ,
               '4' => 1 ,
               '5' => 1 ,
               '6' => 1 ,
               '7' => 0.5,
               '8' => 0,
               '9' => 0,
               '10' => -1 ,
               'J' => -1 ,
               'Q' => -1 ,
               'K' => -1 ,
               'A' => -1 ,
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
