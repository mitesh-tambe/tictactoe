<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tic Tac Toe</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Tic Tac Toe</h1>
        
        <div class="row">
            <div class="col-md-6">
                <p class="text-center">
                    @if(Session::get('gameOver'))
                        {{ Session::get('message') }}
                    @else
                        Player {{ $currentPlayer }}'s turn
                    @endif
                </p>
        
                <div class="board justify-center">
                    @foreach($board as $index => $cell)
                        <div class="cell">
                            @if($cell === '')
                                <form action="/play" method="POST">
                                    @csrf
                                    <input type="hidden" name="position" value="{{ $index }}">
                                    <button type="submit" {{ Session::get('gameOver') ? 'disabled' : '' }}>&nbsp;</button>
                                </form>
                            @else
                                {{ $cell }}
                            @endif
                        </div>
                    @endforeach
                </div>
        
                @if(Session::get('gameOver'))
                    <div class="alert alert-info text-center mt-3">{{ Session::get('message') }}</div>
                @endif
        
                <div class="text-center my-3">
                    <form action="/restart" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary">Restart Game</button>
                    </form>
                </div>
            </div>
    
            <div class="col-md-6">
                @if($results->isEmpty())
                    <p class="text-center">No game results found.</p>
                @else
                    <h3 class="text-center">Game Results</h3>
                    <table class="table table-bordered table-striped mt-3">
                        <thead>
                            <tr>
                                <th>Sr. No</th>
                                <th>Winner</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($results as $index => $result)
                                <tr>
                                    <td>{{ $results->firstItem() + $index }}</td> 
                                    <td>{{ $result->winner }}</td>
                                    <td>{{ $result->created_at->format('d-m-Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="d-flex justify-content-center">
                        {{ $results->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            </div>
        </div>
    </div>    
</body>
</html>
