<?php

interface BlackJackPlayerInterface
{
     public function getBet( BlackJackGame $game) ;
     public function pay( $amt );
     public function win( );
     public function lose( );
     public function push( );
//     public function bust( );
     public function deal( BlackJackHand $dealer, array $others, BlackJackHand $me ); 

}
