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

     const SECTION = '';

     public static function out( $level, $msg )
     {
//          if ( $level == self::MAIN )
          if ( $level === '' )
               echo "$msg\n";
          else
               echo "$level: $msg \n";
     }
}
