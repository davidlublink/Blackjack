<?php


# Source : Some lady I ran into at a casino
#
# This strategy involves maintaining and recovering principle by taking half of gains and putting it back into your pot.
# This betting system uses winning streaks to make up for losing streaks. The longer your streak, the more money that is bet
# each round. The amount of money bet increases with each win and is reset to the minimum bet when lost.
# On the first win, you take the entire amount that was won.
# On the second win, you take half the amount that was won.
# On each subsequent win, you take between 50% and 90% of the win.
#
# If maximum bet is reached, you take the difference.
#
# This strategy allows the player to survive long enough to reach profitable hands such as blackjack, split 10s against 6 and doubles.
#
# Challenges for this playing strategy : 
# The win method is unaware of how much money was spent ( it's double agnostic )
# I need to modify the simulator to indicate to the player if the player doubled
# # Actually, I just need to override the BlackJackPlayer object and catch when it doubles.
#
# Like all the other betting systems, game play does not actually change. Just betting. Game play is already optimised for maximum wins.
#
# This system involves moving the chips back and forth, but really it could be stated as follows : 
# 1. Always bet at least the minimum amount
# 2. Always bet the minimum amount after a loss
# 3. Never bet more than the maximum
# 4. Put money aside after each win
# 5. Shuffling has no impact on betting strategy
# 6. Type of loss or win is of no importance, all that matters is how much money is gain or lost.
# Possible variation : 7. As the the player's pot increases, the minimum bet can increase.
#
#
Class BlackJackPlayer_SomeLady extends BlackJackPlayer
{
     private $Bet ;

     public function pay( $amount )/*{{{*/
     {
          parent::pay( $amount );
          if ( $this->Bet !== null )
               $this->Bet->pay( $amount );
     }/*}}}*/

     public function shuffle()/*{{{*/
     {
     }/*}}}*/

     public function getBet( BlackJackGame $game ) /*{{{*/
     {
          if ( $this->Bet === null )
               $this->Bet = new SomeLadyBetTracker( 5 ) ;

          return $this->Bet->get();
     }/*}}}*/

     public function lose( )/*{{{*/
     {
          $this->Bet = null;
          parent::lose();
     }/*}}}*/

     public function bust( )/*{{{*/
     {
          $this->Bet = null;
          parent::bust();
     }/*}}}*/

}


Class SomeLadyBetTracker
{
     private $capital = 0 ;

     private $bet     = 0 ; 

     private $net = 0;

     public function __construct( $bet )/*{{{*/
     {
          $this->bet = $bet ;
     }/*}}}*/

     public function pay( $amount )/*{{{*/
     {
          $this->net += $amount ;
     }/*}}}*/

     public function get()/*{{{*/
     {
          BlackJackLog::out( BlackJackLog::BET, "Some lady net is $ {$this->net}");

          $return = (int) ( $this->net * 0.5 );
          if ( $return > 0 && $return < 5 )
               $return += 5 ;

          $return = max ( 5, min ( 100, $return ) ); 

          BlackJackLog::out( BlackJackLog::BET, "Some lady betting $ $return");
          return $return ;
     }/*}}}*/

     public function __destruct()
     {
          BlackJackLog::out( BlackJackLog::BET, "Some lady net is $ {$this->net}");
     }

}
