<?php

require_once('PlayerInterface.php');
require_once('Player.php');

require_once('FakeHand.php');
require_once('Deck.php');

$player = new BlackJackPlayer();

echo '<style>';
echo "\n";
echo <<<CSS

.Stand  { background-color : red; }
.Split  { background-color : blue; }
.Hit    { background-color : yellow ; }
.Double { background-color : green ; }


CSS;
echo "\n";
echo '</style>';

echo '<table>';


echo '<tr>';
echo '<th>x</th>';
foreach ( BlackJackDeck::getBase() as $base )
     echo '<th>'.$base.'</th>';
echo '</tr>';

for ( $i = 2; $i <= 20; $i++ )
{
     echo '<tr>';
     echo '<th>'.$i.'</th>';

     foreach ( BlackJackDeck::getBase() as $base )
     {
          $hand = new FakeBlackJackHand( $i );
          $dealer = new FakeBlackJackHand( $base );
          $player->deal( $dealer, array(), $hand ); 
          $result = $hand->getResult();
          if ( $result === null ) $result = 'Stand';

          echo  "<td class='".$result."'> $base/$i =>".$result.'</td>';
     }
     echo '</tr>';
}

for ( $i = 20; $i >= 13; $i-- )
{
     echo '<tr>';
     echo '<th>A - '.( $i- 11) .'</th>';

     foreach ( BlackJackDeck::getBase() as $base )
     {
          $hand = new FakeBlackJackHand( $i, true );

          $dealer = new FakeBlackJackHand( $base );
          $player->deal( $dealer, array(), $hand ); 
          $result = $hand->getResult();
          if ( $result === null ) $result = 'Stand';

          echo  "<td class='".$result."'> $base/$i =>".$result.'</td>';
     }
     echo '</tr>';
}

for ( $i = 20; $i >= 2; $i -= 2 )
{
     echo '<tr>';
     echo '<th>'.($i/2).'-'.($i/2).'</th>';

     foreach ( BlackJackDeck::getBase() as $base )
     {
          $hand = new FakeBlackJackHand( $i, $i === 2, true );

          $dealer = new FakeBlackJackHand( $base );
          $player->deal( $dealer, array(), $hand ); 
          $result = $hand->getResult();
          if ( $result === null ) $result = 'Stand';

          echo  "<td class='".$result."'> $base/$i =>".$result.'</td>';
     }
     echo '</tr>';
}

echo '</table>';
