# tictactoe
Tic Tac Toe

This is a server side code for a custom slack 'slash command' that allows users to play tic tac toe in slack.

##Summary##

Create a slash command /ttt to play tic-tac-toe within Slack. If you’re unfamiliar with the rules, check them out at https://en.wikipedia.org/wiki/Tic-tac-toe. The display as well as the slash command syntax the users must use is entirely up to you. We'd like to see where you take this basic idea!
SAMPLE TIC TAC TOE BOARD
Feel free to use this board as a starting point, or design your own (you can use 
``` to display pre-formatted text in Slack):
| X | O | O |
|---+---+---|
| O | X | X |
|---+---+---|
| X | O | X |
```

##Requirements##

1. Users can create a new game in any Slack channel by challenging another user (using their @username).
2. A channel can have at most one game being played at a time.
3. Anyone in the channel can run a command to display the current board and list whose turn it is.
4. Users can specify their next move, which also publicly displays the board in the channel after the move with a reminder of whose turn it is.
5. Only the user whose turn it is can make the next move.
6. When a turn is taken that ends the game, the response indicates this along with who won.
