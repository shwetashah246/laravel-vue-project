<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ScoreService;
use App\Services\LeaderBoardService;

class HomeController extends Controller {

    public function index() {
        return view('home');
    }

    public function user(ScoreService $score, Request $request) {
        $result = $score->scores($request->all());
        if(!empty($result['errors'])) { return response()->json($result,422); }

        return response()->json($result,200);
    }

	public function leader(LeaderBoardService $board) {
		$res = $board->getLeaders();
        return response()->json($res,200);
	}
}
