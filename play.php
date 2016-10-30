#!/usr/bin/php
<?php

require_once('Game.php');
require_once('Player.php');

$start = array_key_exists( 2, $argv ) ? $argv[2]  : 100 ;

$bj = new BlackJackGame();

$players = array(); 
$players[] = new BlackJackPlayer( $start ); 

#require_once( 'Players/HiLoOpt1.php' ); $players[] = new BlackJackPlayer_HiLoOpt1( $start );
#require_once( 'Players/HiLoOpt2.php' ); $players[] = new BlackJackPlayer_HiLoOpt2( $start );
require_once( 'Players/HiLo.php' );     $players[] = new BlackJackPlayer_HiLo( $start );
require_once( 'Players/HiLoCount.php' );     $players[] = new BlackJackPlayer_HiLoCount( $start );
#require_once( 'Players/OmegaII.php' );  $players[] = new BlackJackPlayer_OmegaII( $start );
#require_once( 'Players/Red7.php' );     $players[] = new BlackJackPlayer_Red7( $start );
#require_once( 'Players/Tek.php' );      $players[] = new BlackJackPlayer_Tek( $start );
#require_once( 'Players/ZenCount.php' ); $players[] = new BlackJackPlayer_ZenCount( $start );
#require_once( 'Players/Martingale.php' ); $players[] = new BlackJackPlayer_Martingale( $start );
require_once( 'Players/Tek2.php' );      $players[] = new BlackJackPlayer_Tek2( $start );
require_once( 'Players/SomeLady.php' );      $players[] = new BlackJackPlayer_SomeLady( $start );

$max = $start;

$original = $roundsRemaining = array_key_exists(1, $argv) ? $argv[1] : 100000;
$hands = 0;

$origPlayers = $players ;
$rounds = array();

try
{
     while ( $roundsRemaining-- > 0 && count($players) )
     {
          $thisRound = array(); 
          foreach ( $players as $k => $player )
          {
               if ( !$player->hasMoney( $bj ) )
               {
                    BlackJackLog::out( BlackJackLog::MAIN, "Player $k (".get_class( $player ) .") is out of money with $roundsRemaining rounds remaining, left the table" );

                    unset($players[$k] );
               }
               elseif ( !$player->skipRound($bj) ) 
               {
                    //BlackJackLog::out( BlackJackLog::MAIN, "Player $k is playing.");
                    $thisRound[] = $player ;
                    if ( !array_key_exists( $k, $rounds) ) $rounds[$k] = 0 ;
                    $rounds[$k]++;
                    if ( $roundsRemaining % 1000 === 0 )
                         BlackJackLog::out( BlackJackLog::MAIN, get_class($player)." : Player has {$player->getMoney()} after {$rounds[$k]} played") ;
                    $rounds[$k]++;
               }
               Else
                    BlackJackLog::out( BlackJackLog::MAIN, "Player $k is skipping out because of a low count."); 
          }

          if ( count( $players ) === 0 ) throw new exception("Everyone is bankrupt!"); 
          if ( count( $thisRound ) === 0 ) throw new exception( "Everyone is sitting out.") ;

          $hands++; 
          $bj->deal( $thisRound );
     }
}
catch(exception $e )
{
     BlackJackLog::out( BlackJackLog::MAIN, "Exception : " ); 
     BlackJackLog::out( BlackJackLog::MAIN, $e->getMessage() ); 
}

BlackJackLog::out( BlackJackLog::MAIN, '');
BlackJackLog::out( BlackJackLog::MAIN, '');
BlackJackLog::out( BlackJackLog::MAIN, '');

BlackJackLog::out( BlackJackLog::MAIN, 

               str_pad( "Player", 35, ' ') 
               ." "
               .str_pad( "Peak", 10, ' ')
               ." "

               . str_pad( "Hands", 10, ' ') 
               . " "
               . str_pad( 'Result' ,7,' ' ) 
               . " "
               .str_pad( "Diff", 7, ' ' )
               . " "
               . str_pad( "Balance", 7, ' ' ) 
               );

usort( $origPlayers, 'playersort') ;

function playerSort( $b, $a ) 
{
     return $a->getMoney() - $b->getMoney();
}

foreach ( $origPlayers as $k => $player )
{
     $gain = $player->getMoney() - $start ;

     if ( array_key_exists( $k, $rounds ) )
          $hands = $rounds[$k];
     else
          $hands = 0;

     BlackJackLog::out( BlackJackLog::MAIN, str_pad(get_class($player), 35 , ' ') 
               ." "
               .str_pad( $player->getPeak(), 10, ' ')
               . " "
               . str_pad($hands, 10, ' ') 
               . " "
               . str_pad( $gain > 0  ? 'Win' : 'Lose' ,7,' ' ) 
               . " "
               .str_pad( abs($gain), 7, ' ' )
               ." "
               . str_pad( $player->getMoney(),7,' ' ) 
               );

}







