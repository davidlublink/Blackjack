<?php

Class BlackJackDeck
{
     private static $sdeck = array( 'A','2','3','4','5','6','7','8','9','10','J','Q','K' );

     const SHUFFLES  = 3  ; 

	private $deck = array();

     private $decks = 8;

     private $count = 0;

     private static $countingSystem = array(/*{{{*/
                'A' => -1 ,
                '2' => 1 ,
                '3' => 1 ,
                '4' => 1 ,
                '5' => 1 ,
                '6' => 1 ,
                '7' => 0,
                '8' => 0,
                '9' => 0,
                '10' => -1 ,
                'J' => -1 ,
                'Q' => -1 ,
                'K' => -1 ,
               );/*}}}*/

	public function __construct()/*{{{*/
	{
          for ( $i = 0; $i < $this->decks * 4; $i++ )
               $this->deck = array_merge( $this->deck, self::$sdeck ); 

          $this->shuffle();
	}/*}}}*/

     private function shuffle()/*{{{*/
     {
          for ( $i = 0; $i < self::SHUFFLES; $i++ )
               shuffle( $this->deck ); 

     }/*}}}*/

     public function getValue( $card )/*{{{*/
     {
          if ( $card === 'A' ) return 1;

          if ( in_array( $card, array('J', 'Q', 'K' ) ) ) return 10;

          return $card; 
     }/*}}}*/

     public function getCount()/*{{{*/
     {
          return $this->count ;
     }/*}}}*/

     public function getTrueCount()/*{{{*/
     {
          return round ( $this->count / count($this->deck ) / 54,1 ) ;
     }/*}}}*/

     public function getRealCount()/*{{{*/
     {
          return $this->count / $this->decks ;
     }/*}}}*/

     public function isCardAvailable()/*{{{*/
     {
          return count($this->deck) > 0 ;
     }/*}}}*/

     public function getCardsRemaining()/*{{{*/
     {
          return count($this->deck) ;
     }/*}}}*/

     private $stacked = array ( );

     public function draw()/*{{{*/
     {
          if ( count($this->stacked ) ) return array_shift( $this->stacked );

          if ( !$this->isCardAvailable() ) throw new exception("Out of cards!");

          $card = array_pop( $this->deck );

          $this->count += self::$countingSystem[ $card ] ;

          echo "Drew card $card\n";

          return $card;
     }/*}}}*/

}
