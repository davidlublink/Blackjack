<?php

Class BlackJackPlayer implements BlackJackPlayerInterface
{
     const BUST = 4;
     const LOSE = 4;
     const WIN  = -5;
     const BLACKJACK = -5;
     const PUSH = -1;

     private $money = 0 ; 
     private $peak = 0; 

     private $nextBet = 0;

     public function __construct( $startingAmount = 20 )/*{{{*/
     {
          $this->money = $startingAmount; 
     }/*}}}*/

     public function getBet( BlackJackGame $game ) { /*{{{*/

          $bet = 15; 

          $count = $game->getCount(); 

          if ( $count < -5 )  throw new exception("player left because count is too low!");

          if( $count > 0 ) 
               $bet += round( 5 * $game->getCount()); 
          else
               $bet += round( 3 * $game->getCount()); 

//          $bet += $this->nextBet ;

          $bet = max( $game->getMinBet(), min( $game->getMaxBet(), $bet ) ); 

          echo "I am betting $bet$\n"; 

          return $bet ;
     }/*}}}*/
     
     public function pay( $amt ) { /*{{{*/
          $this->money += $amt ;
          echo "I have {$this->money}$ left \n";

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
          $this->nextBet = max( $this->nextBet + self::WIN, 0 );
          echo "Player won\n";
     }/*}}}*/

     public function blackjack( )/*{{{*/
     {
          $this->nextBet = max( $this->nextBet + self::BLACKJACK, 0 );
          echo "Player got blackjack\n";
     }/*}}}*/

     public function lose( )/*{{{*/
     {
          $this->nextBet += self::LOSE ;
          echo "Player loses \n";
     }/*}}}*/

     public function bust( )/*{{{*/
     {
          $this->nextBet += self::BUST ;
          echo "I lost \n";
     }/*}}}*/

     public function push(  )/*{{{*/
     {
          echo "Push \n";
          $this->nextBet += self::PUSH;
     }/*}}}*/

     public function deal( BlackJackHand $dealerHand, array $others, BlackJackHand $me ) /*{{{*/
     {
          $dealer = $dealerHand->getShown();

          $i = 15;

          while ( $i-- > 0  )
          {
               list( $soft, $value ) = $me->getValue(); 

               if ( $me->isSplitAllowed() ) 
               {
                    switch ( $me->getShown( ) )
                    {
                         case 'A' :
                         case '8' :
                              $me->split( $dealerHand, $others );
                              break;

                         case 'K' :
                         case 'Q' :
                         case 'J' :
                         case '10' :
                              return; // stand
                              break;

                         case '9' :
                              if ( in_array( $dealer, array('7','10','A') ) ) return ; // stand
                              $me->split( $dealerHand, $others );
                              break;
                         case '7' :
                         case '3' :
                         case '2' :
                              if ( in_array( $dealer, array( '8', '9', '10', 'A' ) ) ) $me->hit();
                              else $me->split( $dealerHand, $others );
                              break;
                         case '6' :
                              if ( in_array( $dealer, array( '7', '8', '9', '10', 'A' ) ) ) $me->hit();
                              else $me->split( $dealerHand, $others );
                              break;

                         case '5' :
                              if ( in_array( $dealer, array( '10', 'A' ) ) ) $me->hit();
                              else return $me->double();
                              break;

                         case '4' :
                              if ( in_array( $dealer, array( '5', '6' ) ) ) $me->split( $dealerHand, $others );
                              else $me->hit();
                              break;
                    }
                    continue ;
               }

               if ( $value >= 19 && $soft || $value >= 17 && !$soft ) return ;

               if ( $soft )
               {
                    if ( $value == 18 )
                    {
                         if ( in_array($dealer, array( '2','7','8') ) ) return ;

                         else if ( in_array( $dealer, array( '9', '10', 'A' ) ) ) $me->hit();

                         else if ( in_array( $dealer, array( '3', '4', '5', '6' ) ) )
                         {
                              if ( $me->isDoubleAllowed() ) return $me->double();
                              $me->hit(); 
                         }
                    }

                    elseif ( $value == 17 ) // A & 6 
                    {
                         if ( in_array( $dealer, array( '2', '7', '8', '9', '10', 'A' ) ) ) $me->hit(); 
                         else if ( in_array( $dealer, array( '3', '4', '5', '6' ) ) )
                         {
                              if ( $me->isDoubleAllowed() ) return $me->double();
                              $me->hit(); 
                         }
                    }
                    elseif ( $value == 16 || $value === 15 ) // A & 5 , A & 6
                    {
                         if ( in_array( $dealer, array( '3', '2', '7', '8', '9', '10', 'A' ) ) ) $me->hit(); 
                         else if ( in_array( $dealer, array( '4', '5', '6' ) ) )
                         {
                              if ( $me->isDoubleAllowed() ) return $me->double();
                              $me->hit(); 
                         }
                    }

                    elseif ( $value == 14 || $value === 13 ) // A & 3 , A & 2
                    {
                         if ( in_array( $dealer, array( '3', '2', '4', '7', '8', '9', '10', 'A' ) ) ) $me->hit(); 
                         else if ( in_array( $dealer, array( '5', '6' ) ) )
                         {
                              if ( $me->isDoubleAllowed() ) return $me->double();
                              $me->hit(); 
                         }
                    }
                    continue ;
               }

               if ( $value >= 17 ) return ;

               elseif ( $value > 11 && $value <= 16 
                         && in_array( $dealer, array( '2', '3', '4', '5', '6' ) ) 
                  ) return ;

               elseif ( $value === 12 && in_array( $dealer, array( '3', '2' ) ) ) 
                    $me->hit();
               elseif ( $value === 11 && $dealer != 'A' && $me->isDoubleAllowed() ) 
                    return $card = $me->double(); 
               elseif ( $value === 11 ) 
                    $me->hit(); 
               elseif ( $value === 10 && !in_array($dealer,array( '10', 'A') ) ) 
                    return $me->double(); 
               elseif ( $value === 9 && in_array( $dealer, array( '3', '4', '5', '6' ) ) ) 
                    return $me->double(); 
               elseif ( in_array( $dealer, array('7','8','9','10','J','K','Q','A') ) && $value < 17 ) 
                    $me->hit();
               elseif ( $value === 9 && $dealer === '2' ) 
                    $me->hit(); 
               elseif ( $value <= 8 ) 
                    $me->hit(); 
          }

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

     public function revealcard( $card )
     {
     }

}

