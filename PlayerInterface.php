<?php

interface BlackJackPlayerInterface
{
     // Card was turned face up, use this for counting cards
     public function revealcard ( $card ) ;

     // Deck was shuffled, reset count
     public function shuffle ( ) ;

     // How much do we want to bet for the next hand ?
     public function getBet( BlackJackGame $game) ;

     // Dealer pays the player the indicated amount ( real signed integer )
     public function pay( $amt );

     // Indicate the player has one the most recent hand
     public function win( );

     // Indicate the player has lost the most recent hand
     public function lose( );

     // Indicate the player has pushed on the most recent hand
     public function push( );

     // Cards have been dealt, what will the player do ?
     // To stand, simply return null ;
     public function deal( BlackJackHand $dealer, array $others, BlackJackHand $me ); 

}
