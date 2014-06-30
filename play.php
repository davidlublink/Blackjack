<?php

require_once('Game.php');
require_once('Player.php');

$bj = new BlackJackGame();

$player = new BlackJackPlayer( $start = 100 ); 

$hands = 0;
$max = $start;

$rounds = array_key_exists(1, $argv) ? $argv[1] : 1;

try
{
     while ( $player->hasMoney( $bj ) && $rounds-- )
     {
          if ( $bj->getCount() < -5 ) 
          {
               $bj = new BlackJackGame(); 
               throw new exception("Count is too low!"); 
               echo "Leave table!\n";
          }

          $hands++; 
          $bj->deal( array( $player ) ); 
     }
}
catch(exception $e )
{
     echo $e->getMessage()."\n";
}


$gain = $player->getMoney() - $start ;
if ( $gain > 0 )
     echo "Player walked away with {$player->getMoney()}, that's a gain of {$gain} but peaked at {$player->getPeak()} with $hands played \n" ;
else
     echo "Player walked away with {$player->getMoney()}, that's a loss of ".abs($gain)." but peaked at {$player->getPeak()} with $hands played \n" ;


