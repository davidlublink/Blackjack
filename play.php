<?php

require_once('Game.php');
require_once('Player.php');

$start = 100;

$bj = new BlackJackGame();

#$player = new BlackJackPlayer( $start = 100 ); 
#require_once( 'Players/HiLo.php' );$player = new BlackJackPlayer_HiLo( $start );
require_once( 'Players/HiLoOpt1.php' );$player = new BlackJackPlayer_HiLoOpt1( $start );

$max = $start;

$rounds = array_key_exists(1, $argv) ? $argv[1] : 1;
$hands = 0;

try
{
     while ( $player->hasMoney( $bj ) && $rounds-- )
     {
          $hands++; 
          $bj->deal( array( $player ) ); 
     }
}
catch(exception $e )
{
     echo "Exception : \n";
     echo $e->getMessage()."\n";
}


$gain = $player->getMoney() - $start ;
if ( $gain > 0 )
     echo "Player walked away with {$player->getMoney()}, that's a gain of {$gain} but peaked at {$player->getPeak()} with $hands played \n" ;
else
     echo "Player walked away with {$player->getMoney()}, that's a loss of ".abs($gain)." but peaked at {$player->getPeak()} with $hands played \n" ;

