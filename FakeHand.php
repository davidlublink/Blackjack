<?php

require_once('Hand.php');


class FakeBlackJackHand extends BlackJackHand
{
     private $soft = false;
     private $split = false;
     private $value = false ;

     public function __construct( $value, $soft = false, $split = false )/*{{{*/
     {
          $this->value = $value;
          $this->split = $split;
          $this->soft = $soft ;
     }/*}}}*/

     public function getValue()/*{{{*/
     {
          return array( $this->soft, $this->value );
     }/*}}}*/

     private $decision = null;
     public function split( ) {
          if ( $this->decision === null ) $this->decision = 'Split';
     }
     public function double() 
     {
          if ( $this->decision === null ) $this->decision = 'Double';
     }
     public function getShown() 
     {
          if ( $this->soft ) 
          {
               if ( $this->value === 12 ) return 'A';
               elseif ( $this->value === 2 ) return 'A';
               else return $this->value - 11 ; 
          }
          if ( $this->split && $this->value === '2' ) return 'A';
          if ( $this->split ) return $this->value / 2 ;
          return $this->value ;
     }

     public function hit( )
     {
          if ( $this->decision === null ) $this->decision = 'Hit';
     }

     public function getResult()
     {
          return $this->decision ;
     }

     public function isDoubleAllowed() { return true; }
     public function isSplitAllowed() { return $this->split; }


     public function dealer( $amt ) { throw new exception("Why are you calling me?"); }
     public function dealerBust( ) { throw new exception("Why are you calling me?"); }
     public function dealerBlackJack( ) { throw new exception("Why are you calling me?"); }
     public function blackJack( ) { throw new exception("Why are you calling me?"); }
     public function revealcards() { throw new exception("Why are you calling me?"); }
     public function isBlackJack() { throw new exception("Why are you calling me?"); }
     public function getCards(){ throw new exception("Why are you calling me?"); }

}
