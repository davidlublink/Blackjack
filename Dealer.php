<?php

require_once('PlayerInterface.php');


Class BlackJackDealer implements BlackJackPlayerInterface
{

     public function deal( BlackJackHand $dealer, array $others, BlackJackHand $me )/*{{{*/
     {
          echo "Dealer has : ". implode(' ', $dealer->getCards()) ."\n";
          while ( true )
          {
               list( $soft, $value ) = $dealer->getValue(); 

               if ( $value >= 17 ) return ;

               $dealer->hit(); 
          }
     }/*}}}*/

     public function __construct( $startingAmount = 20 )/*{{{*/
     {
     }/*}}}*/

     public function getBet( BlackJackGame $game ) { /*{{{*/
          throw new exception("Dealer's don't bet");
     }/*}}}*/
     
     public function pay( $amt ) { /*{{{*/
     }/*}}}*/

     public function getMoney( ) /*{{{*/
     { 
     }/*}}}*/

     public function getPeak(  ) /*{{{*/
     { 
     }/*}}}*/

     public function win( )/*{{{*/
     {
     }/*}}}*/

     public function blackjack( )/*{{{*/
     {
     }/*}}}*/

     public function lose( )/*{{{*/
     {
     }/*}}}*/

     public function bust( )/*{{{*/
     {
     }/*}}}*/

     public function push(  )/*{{{*/
     {
     }/*}}}*/

     public function hasMoney( $game = null)/*{{{*/
     {
     }/*}}}*/

}/*}}}*/

