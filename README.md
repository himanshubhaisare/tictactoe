# Tic Tac Toe
This is a server side code for a custom slack 'slash command' that allows users to play tic tac toe in slack.

## Commands ##
Tic Tac Toe commands manual:

1. `/ttt help` : brings up the help menu
2. `/ttt move <position>` : make your move on given position 1 to 9. e.g. `/ttt move 4`
3. `/ttt challenge @user` : challenge @user for a tic tac toe game. e.g. `/ttt challenge @himanshu`
4. `/ttt status` : current game status
5. `/ttt end` : ends current game

Example output when user hits `/ttt challenge @himanshu`
```
    @himanshu2 has challenged @himanshu for Tic Tac Toe!
    @himanshu2 is X
    @himanshu is O
    
    Current game state:
    
    | 1 | 2 | 3 |
    |---+---+---|
    | 4 | 5 | 6 |
    |---+---+---|
    | 7 | 8 | 9 |
    
    @himanshu2 turn to play.
```
   
Example output when game is over.
```
    @himanshu2 is playing Tic Tac Toe with @himanshu.
    @himanshu2 is X
    @himanshu is O
    
    himanshu2 played X at 9.
    
    Current game state:
    
    | X | O | O |
    |---+---+---|
    | 4 | X | 6 |
    |---+---+---|
    | 7 | 8 | X |
    
    @himanshu2 won! GAME OVER!
```

## Tests ##
How to run tests ? Go to this url to run tests.
`http://himanshu-bhaisare.chatly.io/tests/`