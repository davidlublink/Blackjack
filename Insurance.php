<?php

Class BlackJackInsurance
{
     const PAYOUT = 2;

     private $player ;
     private $bet ;

     public function __construct( $player, $bet )/*{{{*/
     {
          $this->player = $player;
          $this->bet    = $bet ;
     }/*}}}*/

     public function payout()/*{{{*/
     {
          $this->player->pay( $this->bet * self::PAYOUT );
     }/*}}}*/

     public static function check( $game, BlackJackHand $dealerHand, array $players, array $bets )/*{{{*/
     {
          if ( $dealerHand->getShown() !== 'A' ) return false;
          
          $insurance = array(); 
          foreach ( $players as $k => $player )
          {
               $cost = ceil( $bets[$k]->getBet() / self::PAYOUT );

               if ( $player->getMoney() < $cost )
                    BlackJackLog::out( BlackJackLog::INSURANCE, "Player $k can't afford insurance" );
               elseif ( $player->wantInsurance( $game, $cost ) )
               {
                    $player->pay( 0 - $cost );
                    BlackJackLog::out( BlackJackLog::INSURANCE, "Player $k took insurance" );
                    $insurance[] = new self( $player, $cost );
               }
               else
               
               BlackJackLog::out( BlackJackLog::INSURANCE, "Player $k refused insurance" );
          }

          list( $soft, $value ) = $dealerHand->getValue() ;

          if ( $value === 21 )
          {
               BlackJackLog::out( BlackJackLog::INSURANCE,  "Paying insurance to ".count($insurance)." players" );
               foreach ( $insurance as $ins )
               {
                    $ins->payout();
               }
          }
          elseif ( count($insurance ) )
               BlackJackLog::out( BlackJackLog::INSURANCE, "No blackjack, not paying anything suckers! ".count($insurance)." suckers") ;
     }/*}}}*/

}
