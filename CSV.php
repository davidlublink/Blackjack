<?php

Class BlackJackCSV
{
     private static $points = array();

     public static function add( $id, $player, $balance )/*{{{*/
     {
          @self::$points[$id][$player] = $balance;
     }/*}}}*/

     public static function store( $file )
     {
          $csv = array();

          $playerNames = array();
          foreach ( self::$points[0] as $player => $data )
               $playerNames[] = $player;

          $csv[] =';' . implode(';', $playerNames );
          
          foreach ( self::$points as $id => $players )
          {
               $tmp = '';
               $tmp .= $id .';';

               foreach ( $playerNames as $name )
               {
                    if ( array_key_exists($name, $players ) )
                         $tmp .= $players[$name] .';';
                    else
                         $tmp .= 0 .';';
               }

               $csv[] = $tmp;
          }
          file_put_contents( $file, implode("\n", $csv ) );
     }
}
