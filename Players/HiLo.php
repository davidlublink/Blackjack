<?php

require_once( 'Player.php' );


Class BlackJackPlayer_HiLo extends BlackJackPlayer
{

     protected $count = 0 ;

     public function getBet( BlackJackGame $game ) /*{{{*/
     {
//          if ( $this->count < -5 ) return 0 ;//  throw new exception("Player left the table because of bad count!");

          $decks = $game->getCardsRemaining() / 54 ;

          $bet = max ( 5, 10 + $this->getTrueCount($game) * 5 ) ;

          BlackJackLog::out( BlackJackLog::BET, "I am betting $bet" );
          return $bet ;
     }/*}}}*/

     private static $countingSystem = array(/*{{{*/
               'A' => -1 ,
               '2' => 1 ,
               '3' => 1 ,
               '4' => 1 ,
               '5' => 1 ,
               '6' => 1 ,
               '7' => 0,
               '8' => 0,
               '9' => 0,
               '10' => -1 ,
               'J' => -1 ,
               'Q' => -1 ,
               'K' => -1 ,
               );/*}}}*/

     public function revealcard( $card )/*{{{*/
     {
          $this->count += self::$countingSystem[ $card ] ;
     }/*}}}*/

     public function shuffle()/*{{{*/
     {
          $this->count = 0 ;
     }/*}}}*/

     protected function getTrueCount($game)/*{{{*/
     {
          return $this->count / ( $game->getCardsRemaining() / 54 ) ;
     }/*}}}*/

     public function wantInsurance( $game, $cost )/*{{{*/
     {
          return $this->getTrueCount( $game ) > 1;
     }/*}}}*/

}
