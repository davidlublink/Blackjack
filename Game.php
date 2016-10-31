<?php

require_once('Log.php' ); 
require_once('Bust.php');
require_once('GameOver.php');
require_once('Deck.php');
require_once('Bet.php');
require_once('Hand.php');
require_once('Dealer.php');



Class BlackJackGame
{
     private $deck = null;

     private $maxBet = 100;

     private $minBet = 5; 

     private $MINCARDS = 52; 

     public function getMaxBet() { return $this->maxBet ; } 

     public function getMinBet() { return $this->minBet ; }

     public function __construct()/*{{{*/
     {
          $this->deck = new BlackJackDeck();
     }/*}}}*/

     private $dealersCards = array();

     public function getCardsRemaining()/*{{{*/
     {
          return $this->deck->getCardsRemaining(); 
     }/*}}}*/

     public function deal( $handCount, $players )/*{{{*/
     {
          if ( $this->deck->getCardsRemaining() < $this->MINCARDS ) 
          {
               BlackJackLog::out( BlackJackLog::DECK, "Shuffling!") ;
               $this->deck = new BlackJackDeck();
               foreach ($players as $player )
                    $player->shuffle() ;
          }

          BlackJackLog::out( BlackJackLog::SECTION, "\n\n============ Round $handCount has started ==========") ;

          $bets = array();

          foreach ( $players as $key => $player )
               $bets[$key] = new BlackJackBet( $this, $player ); 

          $hands = array(); 
          // first card 
          foreach ( $players as $key => $player )
          {
               $hands[ $key ] = new BlackJackHand( $players, $bets[$key], $this->deck, $this->deck->draw() );
               $hands[ $key ]->revealCards(); 
          }

          $dealerHand     = new BlackJackHand( $players, null, $this->deck, $this->deck->draw() );
          $dealerStrategy = new BlackJackDealer() ;

          // second card 
          foreach ( $hands as $key => $hand )
               $hands[$key]->hit(); 

          $dealerHand->hit();

          require_once('Insurance.php');
          BlackJackInsurance::check( $this, $dealerHand, $players, $bets );

          if ( $dealerHand->isBlackJack() )
          {
               $dealerHand->revealcards(); 
               BlackJackLog::out( BlackJackLog::DEALER, "Dealer has blackjack!" );

               foreach ( $players as $k => $player )
               {
                    $hands[$k]->revealcards();
                    $hands[$k]->dealerBlackJack();
               }
               return ;
          }

          $blackjacks = array() ;
          $stands = array();
          foreach ( $players as $k => $player )
          {
               if ( $hands[$k]->isBlackJack() )
               {
                    $hands[$k]->blackjack();
                    continue ;
               }

               try
               {
                    BlackJackLog::out( BlackJackLog::ROUND, "Player hand is ". implode(' ', $hands[$k]->getCards())." against dealer ". ($dealerHand->getShown()) );
                    $player->deal( $dealerHand, $hands, $hands[$k] ); 
                    if ( $hands[$k]->isSurrendered() )
                         BlackJackLog::out( BlackJackLog::ROUND, "Player surrendered!"); 
                    else
                    {
                         BlackJackLog::out( BlackJackLog::ROUND, "Player stands!" );
                         $stands[$k] = $hands[$k]; 
                    }
               }
               catch( BlackJackBust $e )
               {
                    $player->bust();
                    BlackJackLog::out( BlackJackLog::ROUND, "Player bust!" );
               }
          }

          $dealerHand->revealcards(); 

          if ( count($stands) > 0 ) 
          {
               try
               {
                    $dealerStrategy->deal( $dealerHand, $hands, $dealerHand ); 
               }
               catch( BlackJackBust $e )
               {
                    BlackJackLog::out( BlackJackLog::ROUND, "Dealer Bust");
                    foreach ( $stands as $k => $hand )
                         $hand->dealerBust();

                    return ;
               }
               list($soft, $value ) = $dealerHand->getValue(); 

               foreach ( $stands as $k => $hand )
                    $hand->dealer( $value ); 
          }

          BlackJackLog::out( BlackJackLog::SECTION,  "========== Round $handCount has finished ==========" ) ;
          

     }/*}}}*/

}
