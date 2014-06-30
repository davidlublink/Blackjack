<?php

Class BlackJackBust extends Exception {} 
Class BlackJackGameOver extends Exception {} 

interface BlackJackPlayerInterface/*{{{*/
{
     public function getBet( BlackJackGame $game) ;
     public function pay( $amt );
     public function win( );
     public function lose( );
     public function push( );
//     public function bust( );
     public function deal( BlackJackHand $dealer, array $others, BlackJackHand $me ); 

}/*}}}*/

Class BlackJackDeck/*{{{*/
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

}/*}}}*/

Class BlackJackHand/*{{{*/
{
     private $hidden = '';

     private $deck ;
     private $shown = array(); 

     private $bet ;

     private $split = null ;

     public function __construct( $bet, $deck, $first )/*{{{*/
     {
          if ( $bet !== null )
               $this->bet = $bet ;
          $this->deck = $deck ;
          $this->hidden = $first ;
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
               echo "Hit...\n"; 
          $this->shown[] = $d = $this->deck->draw(); 
          if ( count($this->shown) > 1 )
               echo "Hand is now ". $this->hidden . " ". implode (' ', $this->shown )."\n";
          return $d; 
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

          echo "Splitting on ". implode(' ', $this->getCards() )."\n ";

          $this->split = new BlackJackHand( New BlackJackBet( $this->bet->getGame(), $this->bet->getPlayer(), $this->bet->getBet() ),
                    $this->deck,
                    $this->shown[0] );

          $this->split->hit();

          echo "============== Split hand ============\n";
          $this->bet->getPlayer()->deal( $dealer, $others, $this->split );
          echo "============== Done Split ============\n";

          $this->shown = array(); 

          $this->hit(); 
     }/*}}}*/

     private $doubled = false;

     public function double()/*{{{*/
     {
          echo "Player doubled his bet...\n";
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
               echo "User wants to double, but can't because we don't have enough money!\n";
          else
               return true ;
     } 

     public function isSplitAllowed()/*{{{*/
     {
          if ( !(  count( $this->shown ) === 1 && $this->shown[0] === $this->hidden ) ) return false ;

          if ( $this->bet->getBet() > $this->bet->getPlayer()->getMoney() )
               echo "User wants to split, but can't because we don't have enough money!\n";
          else
               return true ;

     }/*}}}*/

}/*}}}*/

Class BlackJackGame/*{{{*/
{
     private $deck = null;

     private $maxBet = 100;

     private $minBet = 5; 

     private $MINCARDS = 30; 

     public function getMaxBet() { return $this->maxBet ; } 

     public function getMinBet() { return $this->minBet ; }

     public function __construct()/*{{{*/
     {
          $this->deck = new BlackJackDeck();
     }/*}}}*/

     private $dealersCards = array();

     public function getCount() { return $this->deck->getCount();  }
     public function getTrueCount() { return $this->deck->getTrueCount();  }

     public function deal( $players )/*{{{*/
     {
          if ( $this->deck->getCardsRemaining() < $this->MINCARDS ) 
          {
               echo "Shuffle!";
               $this->deck = new BlackJackDeck();
          }

          echo "========== ";
          echo 'Round has started';

          echo " ==========\n";

          $bets = array();

          foreach ( $players as $key => $player )
               $bets[$key] = new BlackJackBet( $this, $player ); 

          $hands = array(); 
          // first card 
          foreach ( $players as $key => $player )
               $hands[ $key ] = new BlackJackHand( $bets[$key], $this->deck, $this->deck->draw() );

          $dealerHand     = new BlackJackHand( null, $this->deck, $this->deck->draw() );
          $dealerStrategy = new BlackJackDealer() ;

          // second card 
          foreach ( $hands as $key => $hand )
               $hands[$key]->hit(); 

          $dealerHand->hit();

          if ( $dealerHand->isBlackJack() )
          {
               echo "Dealer has blackjack!\n";
               foreach ( $players as $k => $player )
                    $hands[$k]->dealerBlackJack();
               return ;
          }

          $blackjacks = array() ;
          $stands = array();
          foreach ( $players as $k => $player )
          {
               if ( $hands[$k]->isBlackJack() )
               {
                    $hands[$k]->blackjack();
                    continue ;
               }

               try
               {
                    echo "Player hand is ". implode(' ', $hands[$k]->getCards())." against dealer ". ($dealerHand->getShown())  ."\n";
                    $player->deal( $dealerHand, $hands, $hands[$k] ); 
                    echo "Player stands!\n";
                    $stands[$k] = $hands[$k]; 
               }
               catch( BlackJackBust $e )
               {
                    echo "Player bust!\n";
                    $player->bust();
               }
          }


          if ( count($stands) > 0 ) 
          {
               try
               {
                    $dealerStrategy->deal( $dealerHand, $hands, $dealerHand ); 
               }
               catch( BlackJackBust $e )
               {
                    echo "Dealer bust!\n";
                    foreach ( $stands as $k => $hand )
                         $hand->dealerBust();

                    return ;
               }
               list($soft, $value ) = $dealerHand->getValue(); 

               foreach ( $stands as $k => $hand )
                    $hand->dealer( $value ); 
          }

          echo "========== ";
          echo 'Round has finished';

          echo " ==========\n\n";
          

     }/*}}}*/

}/*}}}*/

Class BlackJackBet /*{{{*/
{
     private $player ;
     private $bet ;
     private $game ;

     public function __construct( $game, $player, $bet = null )/*{{{*/
     {
          $this->game = $game ;
          $this->player = $player ;
          if ( $bet === null )
          {
               $bet = min( $game->getMaxBet(), max( $game->getMinBet(), $player->getBet($game) ) ); 
               $bet = min ( $bet, $player->getMoney() ) ;
          }

          if ( $bet > $player->getMoney() ) throw new exception("Player can't afford this round!");

          $player->pay ( 0 - $bet );
          $this->bet = $bet ;
     }/*}}}*/

     public function getBet()/*{{{*/
     {
          return $this->bet ;
     }/*}}}*/

     public function getPlayer()/*{{{*/
     {
          return $this->player ;
     }/*}}}*/

     public function getGame()/*{{{*/
     {
          return $this->game ;
     }/*}}}*/

     public function double()/*{{{*/
     {
          $this->player->pay( 0 - $this->bet ); 
          $this->bet *= 2;
     }/*}}}*/

     public function dealerBlackJack( $meBlackJack )/*{{{*/
     {
          $this->payout( $meBlackJack ? 1 : 0 );
     }/*}}}*/

     public function blackJack( )/*{{{*/
     {
          $this->payout( 2.5 ); 
     }/*}}}*/

     public function dealerBust( $me )/*{{{*/
     {
          if ( $me > 21 )
               $this->payout(0);
          else
               $this->payout(2);
     }/*}}}*/

     public function dealer( $me, $dealer )/*{{{*/
     {
          if ( $me > 21 || $me < $dealer )
               $this->payout(0);
          elseif ( $me === $dealer )
               $this->payout(1);
          elseif ( $me > $dealer )
               $this->payout(2);
     }/*}}}*/

     private $paid = false;
     private function payout ( $ratio ) /*{{{*/
     {
          if ( $this->paid ) throw new exception("Double payout!");

          if ( $ratio === 0 ) $this->player->lose();
          if ( $ratio === 1 ) $this->player->push();
          if ( $ratio === 2 ) $this->player->win();
          if ( $ratio === 2.5 ) $this->player->blackjack();

          $payout = $ratio * $this->bet ;
          echo "Paying player $payout$ \n";
          $this->player->pay( round($payout) );
          $this->paid = true ;
     }/*}}}*/

}/*}}}*/

Class BlackJackPlayer implements BlackJackPlayerInterface/*{{{*/
{
     const BUST = 4;
     const LOSE = 4;
     const WIN  = -5;
     const BLACKJACK = -5;
     const PUSH = -1;

     private $money = 0 ; 
     private $peak = 0; 

     private $nextBet = 0;

     public function __construct( $startingAmount = 20 )/*{{{*/
     {
          $this->money = $startingAmount; 
     }/*}}}*/

     public function getBet( BlackJackGame $game ) { /*{{{*/

          $bet = 15; 

          $count = $game->getCount(); 

          if( $count > 0 ) 
               $bet += round( 5 * $game->getCount()); 
          else
               $bet += round( 3 * $game->getCount()); 

//          $bet += $this->nextBet ;

          $bet = max( $game->getMinBet(), min( $game->getMaxBet(), $bet ) ); 

          echo "I am betting $bet$\n"; 

          return $bet ;
     }/*}}}*/
     
     public function pay( $amt ) { /*{{{*/
          $this->money += $amt ;
          echo "I have {$this->money}$ left \n";

          $this->peak = max( $this->money, $this->peak );
     }/*}}}*/

     public function getMoney( ) /*{{{*/
     { 
          return $this->money ; 
     }/*}}}*/

     public function getPeak(  ) /*{{{*/
     { 
          return $this->peak ; 
     }/*}}}*/

     public function win( )/*{{{*/
     {
          $this->nextBet = max( $this->nextBet + self::WIN, 0 );
          echo "Player won\n";
     }/*}}}*/

     public function blackjack( )/*{{{*/
     {
          $this->nextBet = max( $this->nextBet + self::BLACKJACK, 0 );
          echo "Player got blackjack\n";
     }/*}}}*/

     public function lose( )/*{{{*/
     {
          $this->nextBet += self::LOSE ;
          echo "Player loses \n";
     }/*}}}*/

     public function bust( )/*{{{*/
     {
          $this->nextBet += self::BUST ;
          echo "I lost \n";
     }/*}}}*/

     public function push(  )/*{{{*/
     {
          echo "Push \n";
          $this->nextBet += self::PUSH;
     }/*}}}*/

     public function deal( BlackJackHand $dealerHand, array $others, BlackJackHand $me ) /*{{{*/
     {
          $dealer = $dealerHand->getShown();

          $i = 15;

          while ( $i-- > 0  )
          {
               list( $soft, $value ) = $me->getValue(); 

               if ( $me->isSplitAllowed() ) 
               {
                    switch ( $me->getShown( ) )
                    {
                         case 'A' :
                         case '8' :
                              $me->split( $dealerHand, $others );
                              break;

                         case 'K' :
                         case 'Q' :
                         case 'J' :
                         case '10' :
                              return; // stand
                              break;

                         case '9' :
                              if ( in_array( $dealer, array('7','10','A') ) ) return ; // stand
                              $me->split( $dealerHand, $others );
                              break;
                         case '7' :
                         case '3' :
                         case '2' :
                              if ( in_array( $dealer, array( '8', '9', '10', 'A' ) ) ) $me->hit();
                              else $me->split( $dealerHand, $others );
                              break;
                         case '6' :
                              if ( in_array( $dealer, array( '7', '8', '9', '10', 'A' ) ) ) $me->hit();
                              else $me->split( $dealerHand, $others );
                              break;

                         case '5' :
                              if ( in_array( $dealer, array( '10', 'A' ) ) ) $me->hit();
                              else return $me->double();
                              break;

                         case '4' :
                              if ( in_array( $dealer, array( '5', '6' ) ) ) $me->split( $dealerHand, $others );
                              else $me->hit();
                              break;
                    }
                    continue ;
               }

               if ( $value >= 19 && $soft || $value >= 17 && !$soft ) return ;

               if ( $soft )
               {
                    if ( $value == 18 )
                    {
                         if ( in_array($dealer, array( '2','7','8') ) ) return ;

                         else if ( in_array( $dealer, array( '9', '10', 'A' ) ) ) $me->hit();

                         else if ( in_array( $dealer, array( '3', '4', '5', '6' ) ) )
                         {
                              if ( $me->isDoubleAllowed() ) return $me->double();
                              $me->hit(); 
                         }
                    }

                    if ( $value == 17 ) // A & 6 
                    {
                         if ( in_array( $dealer, array( '2', '7', '8', '9', '10', 'A' ) ) ) $me->hit(); 
                         else if ( in_array( $dealer, array( '3', '4', '5', '6' ) ) )
                         {
                              if ( $me->isDoubleAllowed() ) return $me->double();
                              $me->hit(); 
                         }
                    }
                    if ( $value == 16 || $value === 15 ) // A & 5 , A & 6
                    {
                         if ( in_array( $dealer, array( '3', '2', '7', '8', '9', '10', 'A' ) ) ) $me->hit(); 
                         else if ( in_array( $dealer, array( '4', '5', '6' ) ) )
                         {
                              if ( $me->isDoubleAllowed() ) return $me->double();
                              $me->hit(); 
                         }
                    }

                    if ( $value == 14 || $value === 13 ) // A & 3 , A & 2
                    {
                         if ( in_array( $dealer, array( '3', '2', '4', '7', '8', '9', '10', 'A' ) ) ) $me->hit(); 
                         else if ( in_array( $dealer, array( '5', '6' ) ) )
                         {
                              if ( $me->isDoubleAllowed() ) return $me->double();
                              $me->hit(); 
                         }
                    }
               }

               if ( $value >= 17 ) return ;

               if ( $value > 11 && $value <= 16 
                         && in_array( $dealer, array( '2', '3', '4', '5', '6' ) ) 
                  ) return ;

               if ( $value === 12 && in_array( $dealer, array( '3', '2' ) ) ) $me->hit();

               if ( $value === 11 && $dealer != 'A' && $me->isDoubleAllowed() ) 
                    return $card = $me->double(); 

               if ( $value === 11 ) $me->hit(); 

               if ( $value === 10 && !in_array($dealer,array( '10', 'A') ) ) return $me->double(); 

               if ( $value === 9 && in_array( $dealer, array( '3', '4', '5', '6' ) ) ) return $me->double(); 

               if ( in_array( $dealer, array('7','8','9','10','J','K','Q','A') ) && $value < 17 ) $me->hit();

               if ( $value === 9 && $dealer === '2' ) $me->hit(); 
               if ( $value <= 8 ) $me->hit(); 
          }

          die("What do I do ? $dealer <=> $value ");

     }/*}}}*/

     public function hasMoney( $game = null)/*{{{*/
     {
          if ( $game === null )
               return $this->money > 0; 
          elseif ( $game->getMinBet() > $this->money )
               return false;
          return true;
     }/*}}}*/

}/*}}}*/

Class BlackJackDealer extends BlackJackPlayer/*{{{*/
{

     public function deal( BlackJackHand $dealer, array $others, BlackJackHand $me )/*{{{*/
     {
          echo "Dealer has : ". implode(' ', $dealer->getCards()) ."\n";
          while ( true )
          {
               list( $soft, $value ) = $dealer->getValue(); 

               if ( $value >= 17 ) return ;

               $dealer->hit(); 
          }
     }/*}}}*/

}/*}}}*/

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


