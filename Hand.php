<?php


Class BlackJackHand
{
     private $hidden = '';

     private $deck ;
     private $shown = array(); 

     private $bet ;

     private $split = null ;

     private $players ;

     private $show = false;

     public function __construct( $players, $bet, $deck, $first, $show = false )/*{{{*/
     {
          $this->players = $players;
          if ( $bet !== null )
               $this->bet = $bet ;
          $this->deck = $deck ;
          $this->hidden = $first ;
          $this->show = $show ;
     }/*}}}*/

     public function dealer( $amt )/*{{{*/
     {
          if ( $this->bet === null ) return ;

          if ( $this->split !== null ) $this->split->dealer( $amt ); 

          if ( $this->isBlackJack() ) return $this->bet->blackjack();
               
          list($soft, $value) = $this->getValue( false );
          $this->bet->dealer( $value, $amt ); 
     }/*}}}*/

     public function dealerBust( )/*{{{*/
     {
          if ( $this->bet === null ) return ;

          if ( $this->split !== null ) $this->split->dealerBust( ); 
          
          list($soft, $value) = $this->getValue( false );
          $this->bet->dealerBust( $value ); 
     }/*}}}*/

     public function dealerBlackJack( )/*{{{*/
     {
          if ( $this->bet === null ) return ;

          if ( $this->split !== null ) $this->split->dealerBlackJack( $amt ); 
          
          $this->bet->dealerBlackJack( $this->isBlackJack() ); 
     }/*}}}*/

     public function blackJack( )/*{{{*/
     {
          $this->bet->blackjack(); 
     }/*}}}*/

     public function hit( $hideHit = false )/*{{{*/
     {
          if ( $this->doubled ) return ;

          if ( count($this->shown ) > 0 && !$hideHit) 
               BlackJackLog::out( BlackJackLog::PLAY, "Hit..." );

          $this->shown[] = $d = $this->deck->draw(); 
          if ( $this->show )
          {
               foreach ( $this->players as $player )
                    $player->revealcard( $d );
          }

          if ( count($this->shown) > 1 )
               BlackJackLog::out( BlackJackLog::PLAY, array_pop($this->getValue(false)) .": Hand is now ". $this->hidden . " ". implode (' ', $this->shown ) );
          return $d; 
     }/*}}}*/

     public function revealcards()/*{{{*/
     {
          $this->show = true ;
          foreach ( $this->players as $player )
          {
               $player->revealcard( $this->hidden );
               foreach ( $this->shown as $card )
                    $player->revealcard( $card );
          }
     }/*}}}*/

     public function getShown() /*{{{*/
     {
          return $this->hidden ;
     }/*}}}*/

     public function getValue( $exceptOnBust = true )/*{{{*/
     {
          $value = $this->deck->getValue( $this->hidden ); 

          $isSoft = $this->hidden === 'A' ;
          foreach ( $this->shown as $card )
          {
               if ( $card === 'A' ) $isSoft = true;

               $value += $this->deck->getValue( $card );
          }

          if ( $value <= 11 && $isSoft )
               return array( true, $value + 10 );

          if ( $value > 21 && $exceptOnBust ) throw new BlackJackBust("Hand value is $value"); 

          return array( false, $value ) ;
     }/*}}}*/

     public function isBlackJack()/*{{{*/
     {
          if ( count($this->shown) !== 1 ) return false;

          list( $soft, $value ) = $this->getValue(false) ;

          return $value === 21 && $soft ;
     }/*}}}*/

     public function getCards()/*{{{*/
     {
          return array_merge( array($this->hidden) , $this->shown );
     }/*}}}*/

     public function split( $dealer, $others )/*{{{*/
     {
          if ( !$this->isSplitAllowed() ) throw new exception("You can't split now!");

          BlackJackLog::out( BlackJackLog::PLAY, "Splitting on ". implode(' ', $this->getCards() ) );

          $this->split = new BlackJackHand( $this->players, New BlackJackBet( $this->bet->getGame(), $this->bet->getPlayer(), $this->bet->getBet() ),
                    $this->deck,
                    $this->shown[0], true );

          $this->split->hit();

          BlackJackLog::out( BlackJackLog::PLAY, "============== Split hand ============"); 
          $this->bet->getPlayer()->deal( $dealer, $others, $this->split );
          BlackJackLog::out( BlackJackLog::PLAY, "============== Done Split ============");

          $this->shown = array(); 

          $this->hit(); 
     }/*}}}*/

     private $doubled = false;

     public function doubleOrStand()
     {
          $this->double( false ); 
     }

     public function double($hit = true)/*{{{*/
     {
          if ( !$this->isDoubleAllowed() ) 
               return $hit ? $this->hit() : null ;  

          BlackJackLog::out( BlackJackLog::PLAY, "Player doubled his bet...");
          $card = $this->hit(true);
          if ( count( $this->shown ) === 2 ) 
          {
               $this->doubled = true;
               $this->bet->double();
          }
          $this->getValue(); 

          return $card; 
     }/*}}}*/

     public function isDoubleAllowed() 
     {
          if ( count($this->shown) !== 1 ) return false; 

          if ( $this->bet->getBet() > $this->bet->getPlayer()->getMoney() )
               BlackJackLog::out( BlackJackLog::PLAY, "User wants to double, but can't because we don't have enough money!" );
          else
               return true ;
     } 

     public function isSplitAllowed()/*{{{*/
     {
          if ( !(  count( $this->shown ) === 1 && $this->shown[0] === $this->hidden ) ) return false ;

          if ( $this->bet->getBet() > $this->bet->getPlayer()->getMoney() )
               BlackJackLog::out( BlackJackLog::PLAY, "User wants to split, but can't because we don't have enough money!" );
          else
               return true ;

     }/*}}}*/

}
