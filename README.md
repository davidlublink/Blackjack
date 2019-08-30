Blackjack
=========

Casino blackjack simulator

Run play.php with php 5.3 or later.

If you want to test a different counting system or basic strategy, simple implement PlayerInterface.

Load up the player and run play.php

The only result I really care about when running the script is whether or not the player goes bankrupt. If the player can play an infinit number of rounds without losing, the player probably has a good system. 

If the player can't last more than a few hundred rounds, the player's strategy is no good.

I like playing Blackjack at the casino, but I run strategies through this automated blackjack player to see if the strategy will actually work or not. I run it through 100,000 rounds. If after 100,000 rounds, the automated player has more money remaining than the player started with, I consider the strategy to be good.

Keep in mind that when playing blackjack, the house always has a slight advantage, and their is unlikely a strategy that works consistently.

But I don't care, it's still fun to try out new strategies.

Known issues
=========

* There may be an issue with my player implementations because even on a single deck, the card counters always do worse than the reference blackjack player

Notes 
=========
This software has no user interface. If you don't know how PHP works, you probably won't be able to do anything useful with this software. If you do understand PHP, you might be able to do something mildly useful.

This code is licensed under GPL v3. http://www.gnu.org/copyleft/gpl.html
