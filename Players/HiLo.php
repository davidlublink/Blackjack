<?php

require_once( 'Player.php' );


Class BlackJackPlayer_HiLo extends BlackJackPlayer
{

     protected $count = 0 ;

     private $game ;

     public function getBet( BlackJackGame $game ) /*{{{*/
     {
          $this->game = $game ;
//          if ( $this->count < -5 ) return 0 ;//  throw new exception("Player left the table because of bad count!");

          $decks = $game->getCardsRemaining() / 52 ;

          $bet = max ( 5, round( 10 + $this->getTrueCount($game) * 5 ) ) ;

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
          return $this->count / ( $game->getCardsRemaining() / 52 ) ;
     }/*}}}*/

     public function wantInsurance( $game, $cost )/*{{{*/
     {
          return $this->getTrueCount( $game ) >= 3;
     }/*}}}*/

     public function deal( BlackJackHand $dealerHand, array $others, BlackJackHand $me ) /*{{{*/
     {
          if ( $this->game === null ) return parent::deal($dealerHand, $others, $me ); 

          $dealer = $dealerHand->getShown();

          $i = 3;

          $count = $this->getTrueCount($this->game) ;

          while ( $i-- > 0  )
          {
               list( $soft, $value ) = $me->getValue(); 

               // Illustrious 2
               if ( $count >= 0 && $value === 16 && in_array( $dealer, array( '10', 'J', 'Q', 'K', 'A' ) ) ) 
               {
                    return ;
               }

               // Illustrious 3
               if ( $count >= 4 && $value === 15 && in_array( $dealer, array('10', 'J', 'Q', 'K', 'A' ) ) )
               {
                    return ;
               }

               // Illustrious 4
               if ( $count >= 5 && $value === 20 && $me->isSplitAllowed() && in_array( $dealer, array('5') ) )
               {
                    $me->split($dealerHand, $others); 
                    return ;
               }

               // Illustrious 5
               if ( $count >= 4 && $value === 20 && $me->isSplitAllowed() && in_array( $dealer, array('6') ) )
               {
                    $me->double();
                    return ;
               }

               // Illustrious 6
               if ( $count >= 4 && $value === 10 && $me->isDoubleAllowed() && in_array( $dealer, array('10') ) )
               {
                    $me->double();
                    return ;
               }

               // Illustrious 7
               if ( $count >= 2 && $value === 12 && in_array( $dealer, array('3') ) )
               {
                    return ;
               }

               // Illustrious 8
               if ( $count >= 3 && $value === 12 && in_array( $dealer, array('2') ) )
               {
                    return ;
               }

               // Illustrious 9
               if ( $count >= 1 && $value === 11 && in_array( $dealer, array('A') ) )
               {
                    $me->double(); // /r/Moogra2u said should should be double not stand
                    return ;
               }

               // Illustrious 10
               if ( $count >= 1 && $value === 9 && in_array( $dealer, array('2') ) )
               {
                    $me->double(); // /r/Moogra2u said should should be double not stand
                    return ;
               }

               // Illustrious 11
               if ( $count >= 4 && $value === 10 && in_array( $dealer, array('A') ) )
               {
                    $me->double();
                    return ;
               }

               // Illustrious 12
               if ( $count >= 3 && $value === 9 && in_array( $dealer, array('7') ) )
               {
                    $me->double();
                    return ;
               }

               // Illustrious 13
               if ( $count >= 5 && $value === 16 && in_array( $dealer, array('9') ) )
               {
                    return ;
               }

               // Illustrious 14
               if ( $count >= -1 && $value === 13 && in_array( $dealer, array('2') ) )
               {
                    return ;
               }

               // Illustrious 15
               if ( $count >= 0 && $value === 12 && in_array( $dealer, array('4') ) )
               {
                    return ;
               }

               // Illustrious 16
               if ( $count >= -2 && $value === 12 && in_array( $dealer, array('5') ) )
               {
                    return ;
               }
               elseif ( $value === 12 && in_array( $dealer, array('5') ) )
               {
                    $me->hit();
                    return ;
               }

               // Illustrious 17
               if ( $count >= -1 && $value === 12 && in_array( $dealer, array('6') ) )
               {
                    return ;
               }
               elseif ( $value === 12 && in_array( $dealer, array('6') ) )
               {
                    $me->hit();
                    return ;
               }

               // Illustrious 18
               if ( $count >= -2 && $value === 13 && in_array( $dealer, array('3') ) )
               {
                    return ;
               }
               elseif ( $value === 13 && in_array( $dealer, array('3') ) )
               {
                    $me->hit();
                    return ;
               }

          }


          return parent::deal( $dealerHand, $others, $me );
     } /*}}}*/ 

     public function skipRound($game = null)
     {
          return false ;
          return $this->getTrueCount($game) < 1 ; 
     }

}
