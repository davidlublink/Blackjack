<?php

class BlackJackLog
{
     const DECK = 'Deck';
     const INSURANCE = 'Insurance';
     const RESULTS = 'Result';
     const ROUND = 'Round';
     const MAIN = 'Main';
     const BET = 'Bet';
     const DEALER = 'Dealer';
     const PLAY = 'Play';

     public static function out( $level, $msg )
     {
          if ( $level == self::MAIN )
               echo "$level: $msg \n";
     }
}
