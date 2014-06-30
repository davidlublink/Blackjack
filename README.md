Blackjack
=========

Casino blackjack simulator

Run play.php with php 5.3 or later.

If you want to test a different counting system or basic strategy, simple implement PlayerInterface.

Load up the player and run play.php

The only result I really care about when running the script is whether or not the player goes bankrupt. If the player can play an infinit number of rounds without losing, the player probably has a good system. 

If the player can't last more than a few hundred rounds, the player's strategy sucks.


Known issues
=========
* The player can't see other players cards, I need to update the PlayerInterface so that it is called each time a card is shown.
* Card counting is done in BlackJackDeck and not by the player, this is wrong.


Notes 
=========
This software has no user interface. If you don't know how PHP works, you probably won't be able to do anything useful with this software. If you do understand PHP, you might be able to do something mildly useful.

This code is licensed under GPL v3. http://www.gnu.org/copyleft/gpl.html
