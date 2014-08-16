<?php

require_once( 'HiLo.php' );

# Source : http://en.wikipedia.org/wiki/Card_counting#Systems
Class BlackJackPlayer_OmegaII extends BlackJackPlayer_HiLo
{

     public static $countingSystem = array(/*{{{*/
               '2' => 1 ,
               '3' => 1 ,
               '4' => 2 ,
               '5' => 2 ,
               '6' => 2 ,
               '7' => 1,
               '8' => 0,
               '9' => -1,
               '10' => -2 ,
               'J' => -2 ,
               'Q' => -2 ,
               'K' => -2 ,
               'A' => 0 ,
               );/*}}}*/

     public function revealcard( $card )/*{{{*/
     {
          $this->count += self::$countingSystem[ $card ] ;
     }/*}}}*/

     public function shuffle()/*{{{*/
     {
          $this->count = 0 ;
     }/*}}}*/


}
