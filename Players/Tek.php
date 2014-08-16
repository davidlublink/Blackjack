<?php

require_once( 'HiLo.php' );

# Source : http://en.wikipedia.org/wiki/Card_counting#Systems
Class BlackJackPlayer_Tek extends BlackJackPlayer_HiLo
{

     public static $countingSystem = array(/*{{{*/
               '2' => 1 ,
               '3' => 1 ,
               '4' => 1 ,
               '5' => 1 ,
               '6' => 1 ,
               '7' => 1,
               '8' => 0,
               '9' => 0,
               '10' => -1 ,
               'J' => -1 ,
               'Q' => -1 ,
               'K' => -1 ,
               'A' => -1 ,
               );/*}}}*/

     public function shuffle()/*{{{*/
     {
          $this->bet = 0 ;
     }/*}}}*/

     public function getBet( BlackJackGame $game ) /*{{{*/
     {
          $bet = max ( 5, 5 * $this->bet );
          BlackJackLog::out( BlackJackLog::BET, "I am betting $bet" );
          return $bet ;
     }/*}}}*/

     private $bet = 0;

     const WIN       = -1 ;
     const BLACKJACK = -2 ;
     const LOSE      = 1  ;
     const BUST      = 1  ;

     public function win( )/*{{{*/
     {
          $this->bet += self::WIN ;
          parent::win();
     }/*}}}*/

     public function blackjack( )/*{{{*/
     {
          $this->bet += self::BLACKJACK;
          parent::blackjack();
     }/*}}}*/

     public function lose( )/*{{{*/
     {
          $this->bet += self::LOSE ;
          parent::lose();
     }/*}}}*/

     public function bust( )/*{{{*/
     {
          $this->bet += self::BUST ;
          parent::bust();
     }/*}}}*/

     public function push(  )/*{{{*/
     {
          parent::push();
     }/*}}}*/

     public function deal( BlackJackHand $dealerHand, array $others, BlackJackHand $me ) /*{{{*/
     {
          if ( $this->game === null ) return parent::deal($dealerHand, $others, $me ); 

          $dealer = $dealerHand->getShown();

          $i = 3;

          $count = $this->getTrueCount($this->game) ;

          // Illustrious 4
          if ( $value === 20 && $me->isSplitAllowed() && in_array( $dealer, array('5', '6' ) ) )
          {
               $me->split($dealerHand, $others); 
               return ;
          }

          // Illustrious 5
          if ( $value <= 11 && $me->isDoubleAllowed() && in_array( $dealer, array('6','5') ) )
          {
               $me->double();
               return ;
          }
          return parent::deal($dealerHand,$others,$me );
     } /*}}}*/
 
}
