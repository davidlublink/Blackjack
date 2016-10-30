<?php

require_once( 'HiLo.php' );

# Source : http://en.wikipedia.org/wiki/Card_counting#Systems
Class BlackJackPlayer_HiLoCount extends BlackJackPlayer_HiLo
{

     public function getTrueCount($game) { return $this->count ; } 


}
