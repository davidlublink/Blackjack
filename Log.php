<?php

class BlackJackLog
{
     const DECK = 'Deck';
     const INSURANCE = 'Insurance';
     const RESULTS = 'Result';
     const MAIN = 'Main';
     const BET = 'Bet';
     const DEALER = 'Dealer';
     const PLAY = 'Play';

     public static function out( $level, $msg )
     {
          echo "$level: $msg \n";
     }
}
