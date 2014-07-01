<?php

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

     private $MINCARDS = 30; 

     public function getMaxBet() { return $this->maxBet ; } 

     public function getMinBet() { return $this->minBet ; }

     public function __construct()/*{{{*/
     {
          $this->deck = new BlackJackDeck();
     }/*}}}*/

     private $dealersCards = array();

     public function getCount() { return $this->deck->getCount();  }
     public function getTrueCount() { return $this->deck->getTrueCount();  }

     public function deal( $players )/*{{{*/
     {
          if ( $this->deck->getCardsRemaining() < $this->MINCARDS ) 
          {
               echo "Shuffle!";
               $this->deck = new BlackJackDeck();
          }

          echo "========== ";
          echo 'Round has started';

          echo " ==========\n";

          $bets = array();

          foreach ( $players as $key => $player )
               $bets[$key] = new BlackJackBet( $this, $player ); 

          $hands = array(); 
          // first card 
          foreach ( $players as $key => $player )
               $hands[ $key ] = new BlackJackHand( $bets[$key], $this->deck, $this->deck->draw() );

          $dealerHand     = new BlackJackHand( null, $this->deck, $this->deck->draw() );
          $dealerStrategy = new BlackJackDealer() ;

          // second card 
          foreach ( $hands as $key => $hand )
               $hands[$key]->hit(); 

          $dealerHand->hit();

          if ( $dealerHand->isBlackJack() )
          {
               echo "Dealer has blackjack!\n";
               foreach ( $players as $k => $player )
                    $hands[$k]->dealerBlackJack();
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
                    echo "Player hand is ". implode(' ', $hands[$k]->getCards())." against dealer ". ($dealerHand->getShown())  ."\n";
                    $player->deal( $dealerHand, $hands, $hands[$k] ); 
                    echo "Player stands!\n";
                    $stands[$k] = $hands[$k]; 
               }
               catch( BlackJackBust $e )
               {
                    echo "Player bust!\n";
                    $player->bust();
               }
          }


          if ( count($stands) > 0 ) 
          {
               try
               {
                    $dealerStrategy->deal( $dealerHand, $hands, $dealerHand ); 
               }
               catch( BlackJackBust $e )
               {
                    echo "Dealer bust!\n";
                    foreach ( $stands as $k => $hand )
                         $hand->dealerBust();

                    return ;
               }
               list($soft, $value ) = $dealerHand->getValue(); 

               foreach ( $stands as $k => $hand )
                    $hand->dealer( $value ); 
          }

          echo "========== ";
          echo 'Round has finished';

          echo " ==========\n\n";
          

     }/*}}}*/

}
