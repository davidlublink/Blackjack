<?php

Class BlackJackPlayer implements BlackJackPlayerInterface
{
     private $money = 0 ; 
     private $peak = 0; 

     public function __construct( $startingAmount = 20 )/*{{{*/
     {
          $this->money = $startingAmount; 
     }/*}}}*/

     public function getBet( BlackJackGame $game ) /*{{{*/
     {
          return 5;
     }/*}}}*/
     
     public function pay( $amt ) { /*{{{*/
          $this->money += $amt ;
          BlackJackLog::out( BlackJackLog::RESULTS, "I have {$this->money}$ left");

          $this->peak = max( $this->money, $this->peak );
     }/*}}}*/

     public function getMoney( ) /*{{{*/
     { 
          return $this->money ; 
     }/*}}}*/

     public function getPeak(  ) /*{{{*/
     { 
          return $this->peak ; 
     }/*}}}*/

     public function win( )/*{{{*/
     {
          BlackJackLog::out( BlackJackLog::RESULTS, "Player won") ;
     }/*}}}*/

     public function blackjack( )/*{{{*/
     {
          BlackJackLog::out( BlackJackLog::RESULTS, "Player got blackjack"); 
     }/*}}}*/

     public function lose( )/*{{{*/
     {
          BlackJackLog::out( BlackJackLog::RESULTS, "Player loses");
     }/*}}}*/

     public function bust( )/*{{{*/
     {
          BlackJackLog::out( BlackJackLog::RESULTS, "I lost" ) ;
     }/*}}}*/

     public function push(  )/*{{{*/
     {
          BlackJackLog::out( BlackJackLog::RESULTS, "Push" );
     }/*}}}*/

     public function deal( BlackJackHand $dealerHand, array $others, BlackJackHand $me ) /*{{{*/
     {
          $dealer = $dealerHand->getShown();

          $i = 21;
          // This code uses strategy from http://www.blackjackfreeonline.org/wp-content/uploads/2013/11/chart.gif 

          while ( $i-- > 0  )
          {
               list( $soft, $value ) = $me->getValue(); 

               if ( $value === 21 ) 
               {
                    BlackJackLog::out( BlackJackLog::PLAY, "I have 21, nice..." );
                    return ;
               }

               if ( $me->isSplitAllowed() ) 
               {
                    switch ( $me->getShown( ) )
                    {
                         case 'A' :
                              // @verify : should I split A against 10, J, Q, K, A ?
                              $me->split($dealerHand, $others);
                              break;
                         case '8' :
                              // @verify
                              if ( !in_array( $dealer, array('10', 'J', 'Q','K', 'A' ) ) )
                                   $me->split( $dealerHand, $others );
                              else
                                   $me->hit(); 
                              break;

                         case 'K' :
                         case 'Q' :
                         case 'J' :
                         case '10' :
                              return; // stand
                              break;

                         case '9' :
                              if ( in_array( $dealer, array('7','10','J','Q','K','A') ) ) return ; // stand
                              $me->split( $dealerHand, $others );
                              break;
                         case '7' :
                              // @verify : 
                              if ( in_array( $dealer, array( '10', 'J', 'Q', 'K' ) ) ) 
                              {
                                   // stand!!
                                   return ;
                              }
                              if ( in_array( $dealer, array( '8', '9', '10','J','Q','K', 'A' ) ) ) $me->hit();
                              else $me->split( $dealerHand, $others );
                              break;
                         case '3' :
                              if ( in_array( $dealer, array( '3', '2', '8', '9', '10','J','Q','K', 'A' ) ) ) $me->hit();
                              else $me->split( $dealerHand, $others );
                              break;
                         case '2' :
                              if ( in_array( $dealer, array( '2', '8', '9', '10','J','Q','K', 'A' ) ) ) $me->hit();
                              else $me->split( $dealerHand, $others );
                              break;
                         case '6' :
                              if ( in_array( $dealer, array( '7', '8', '9', '10','J','Q','K', 'A' ) ) ) $me->hit();
                              else $me->split( $dealerHand, $others );
                              break;

                         case '5' :
                              if ( in_array( $dealer, array( '10','J','Q','K', 'A' ) ) ) $me->hit();
                              else return $me->double();
                              break;

                         case '4' :
                              // @verify : should I split with 4 against 5 or 6 ?
                              // if ( in_array( $dealer, array( '5', '6' ) ) ) $me->split( $dealerHand, $others ); else 
                                   $me->hit();
                              break;
                    }
                    continue ;
               }

               if ( $value >= 19 && $soft || $value >= 17 && !$soft ) return ;


               if ( $soft )
               {
                    if ( $value === 18 && in_array( $dealer, array( '10', 'J','Q','K' ) ) )
                         $me->hit(); 
                    if ( $value >= 18 ) return ;
                    $me->hit();

                    continue ;
               }
               // alternative strategy
               if ( false && $soft )
               {
                    if ( $value == 18 )
                    {
                         if ( in_array($dealer, array( '2','7','8') ) ) return ;

                         else if ( in_array( $dealer, array( '9', '10','J','Q','K', 'A' ) ) ) $me->hit();

                         else if ( in_array( $dealer, array( '3', '4', '5', '6' ) ) )
                         {
                              if ( $me->isDoubleAllowed() ) return $me->double();
                              $me->hit(); 
                         }
                         continue ;
                    }

                    elseif ( $value == 17 ) // A & 6 
                    {
                         if ( in_array( $dealer, array( '2', '7', '8', '9', '10','J','Q','K', 'A' ) ) ) $me->hit(); 
                         else if ( in_array( $dealer, array( '3', '4', '5', '6' ) ) )
                         {
                              if ( $me->isDoubleAllowed() ) return $me->double();
                              $me->hit(); 
                         }
                         continue ;
                    }
                    elseif ( $value == 16 || $value === 15 ) // A & 5 , A & 6
                    {
                         if ( in_array( $dealer, array( '3', '2', '7', '8', '9', '10','J','Q','K', 'A' ) ) ) $me->hit(); 
                         else if ( in_array( $dealer, array( '4', '5', '6' ) ) )
                         {
                              if ( $me->isDoubleAllowed() ) return $me->double();
                              $me->hit(); 
                         }
                         continue ;
                    }

                    elseif ( $value == 14 || $value === 13 ) // A & 3 , A & 2
                    {
                         if ( in_array( $dealer, array( '3', '2', '4', '7', '8', '9', '10','J','Q','K', 'A' ) ) ) $me->hit(); 
                         else if ( in_array( $dealer, array( '5', '6' ) ) )
                         {
                              if ( $me->isDoubleAllowed() ) return $me->double();
                              $me->hit(); 
                         }
                         continue ;
                    }
               }

               if ( $value >= 17 ) return ;

               elseif ( $value === 12 && in_array( $dealer, array( '3', '2' ) ) ) 
                    $me->hit();
               elseif ( $value > 11 && $value <= 16 
                         && in_array( $dealer, array( '2', '3', '4', '5', '6' ) ) 
                  ) return ;

               // @verify Some strategy guides say never to double against a 10
               elseif ( $value === 11 && !in_array( $dealer, array('10', 'J', 'Q', 'K', 'A' ) ) && $me->isDoubleAllowed() ) 
                    return $card = $me->double(); 
               elseif ( $value === 11 ) 
                    $me->hit(); 
               elseif ( $value === 10 && !in_array($dealer,array( '10','J','Q','K', 'A') ) ) 
                    return $me->double(); 
               // @verify some charts say I should hit, others say double
               elseif ( $value === 9 && in_array( $dealer, array( '2' ) ) )
                    return $me->double(); 
               elseif ( $value === 9 && in_array( $dealer, array( '3', '4', '5', '6' ) ) ) 
                    return $me->double(); 
               elseif ( in_array( $dealer, array('7','8','9','10','J','K','Q','A') ) && $value < 17 ) 
                    $me->hit();
               elseif ( $value === 9 && $dealer === '2' ) 
                    $me->hit(); 
               elseif ( $value <= 8 ) 
                    $me->hit(); 
               else
                    return ;
          }

          return ;
          die("What do I do ? $dealer <=> $value ");

     }/*}}}*/

     public function hasMoney( $game = null)/*{{{*/
     {
          if ( $game === null )
               return $this->money > 0; 
          elseif ( $game->getMinBet() > $this->money )
               return false;
          return true;
     }/*}}}*/

     public function revealcard( $card )/*{{{*/
     {
     }/*}}}*/

     public function shuffle()/*{{{*/
     {
     }/*}}}*/

     public function wantInsurance( $game, $cost )/*{{{*/
     {
          return false ;
     }/*}}}*/

}




