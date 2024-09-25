<?php

namespace App\Http\Controllers;

use App\Models\GameResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class TicTacToeController extends Controller
{
    public function index()
    {
        $board = Session::get('board', array_fill(0, 9, ''));
        $currentPlayer = Session::get('currentPlayer', 'X');
        $message = Session::get('message', '');

        $results = GameResult::orderBy('created_at', 'desc')->paginate(5);

        return view('tictactoe', compact('board', 'currentPlayer', 'message', 'results'));
    }

    public function play(Request $request)
    {
        $position = $request->input('position');
        $board = Session::get('board', array_fill(0, 9, ''));
        $currentPlayer = Session::get('currentPlayer', 'X');
        $message = '';
        $gameOver = Session::get('gameOver', false);
    
        if ($gameOver) {
            return redirect('/');
        }
    
        if ($board[$position] === '') {
            $board[$position] = $currentPlayer;
    
            if ($this->checkWinner($board, $currentPlayer)) {
                $message = "Player $currentPlayer wins!";
                Session::put('gameOver', true);
    
                $this->saveGameResult($board, $currentPlayer);
            } elseif (!in_array('', $board)) {
                $message = "It's a draw!";
                Session::put('gameOver', true);

                $this->saveGameResult($board, null);
            } else {
                $currentPlayer = $currentPlayer === 'X' ? 'O' : 'X';
            }
        } else {
            $message = 'Invalid move! Try again.';
        }
    
        Session::put('board', $board);
        Session::put('currentPlayer', $currentPlayer);
        Session::put('message', $message);
    
        return redirect('/');
    }

  
    private function checkWinner($board, $player)
    {
        $winningCombinations = [
            [0, 1, 2], [3, 4, 5], [6, 7, 8], 
            [0, 3, 6], [1, 4, 7], [2, 5, 8],
            [0, 4, 8], [2, 4, 6]             
        ];

        foreach ($winningCombinations as $combination) {
            if ($board[$combination[0]] === $player &&
                $board[$combination[1]] === $player &&
                $board[$combination[2]] === $player) {
                return true;
            }
        }
        return false;
    }

    // Reset the game
    public function restart()
    {
        Session::flush();
        return redirect('/');
    }

    private function saveGameResult($board, $winner)
    {
        $result = new GameResult();
        $result->winner = $winner ?: 'Draw';
        $result->moves = json_encode($board); 
        $result->save();
    }
}
