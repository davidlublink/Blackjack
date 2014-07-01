<?php

Class BlackJackBet 
{
     private $player ;
     private $bet ;
     private $game ;

     public function __construct( $game, $player, $bet = null )/*{{{*/
     {
          $this->game = $game ;
          $this->player = $player ;
          if ( $bet === null )
          {
               $bet = min( $game->getMaxBet(), max( $game->getMinBet(), $player->getBet($game) ) ); 
               $bet = min ( $bet, $player->getMoney() ) ;
          }

          if ( $bet > $player->getMoney() ) throw new exception("Player can't afford this round!");

          $player->pay ( 0 - $bet );
          $this->bet = $bet ;
     }/*}}}*/

     public function getBet()/*{{{*/
     {
          return $this->bet ;
     }/*}}}*/

     public function getPlayer()/*{{{*/
     {
          return $this->player ;
     }/*}}}*/

     public function getGame()/*{{{*/
     {
          return $this->game ;
     }/*}}}*/

     public function double()/*{{{*/
     {
          $this->player->pay( 0 - $this->bet ); 
          $this->bet *= 2;
     }/*}}}*/

     public function dealerBlackJack( $meBlackJack )/*{{{*/
     {
          $this->payout( $meBlackJack ? 1 : 0 );
     }/*}}}*/

     public function blackJack( )/*{{{*/
     {
          $this->payout( 2.5 ); 
     }/*}}}*/

     public function dealerBust( $me )/*{{{*/
     {
          if ( $me > 21 )
               $this->payout(0);
          else
               $this->payout(2);
     }/*}}}*/

     public function dealer( $me, $dealer )/*{{{*/
     {
          if ( $me > 21 || $me < $dealer )
               $this->payout(0);
          elseif ( $me === $dealer )
               $this->payout(1);
          elseif ( $me > $dealer )
               $this->payout(2);
     }/*}}}*/

     private $paid = false;
     private function payout ( $ratio ) /*{{{*/
     {
          if ( $this->paid ) throw new exception("Double payout!");

          if ( $ratio === 0 ) $this->player->lose();
          if ( $ratio === 1 ) $this->player->push();
          if ( $ratio === 2 ) $this->player->win();
          if ( $ratio === 2.5 ) $this->player->blackjack();

          $payout = $ratio * $this->bet ;
          BlackJackLog::out( BlackJackLog::BET, "Paying player $payout$"); 
          $this->player->pay( round($payout) );
          $this->paid = true ;
     }/*}}}*/

}


