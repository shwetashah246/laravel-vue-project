<?php

namespace App\Services;

use DB;
use App\Models\Score;

class LeaderBoardService  {

	public function getLeaders(){
		DB::select("SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
		$res = Score::groupBy('user_id')->orderBy(DB::raw('count(user_won=1)'),'desc')
					->join('users','users.id','=','scores.user_id')
					->get(['users.user_name',DB::raw('count(*) as total_games'),DB::raw('count(user_won=1) as total_won')]);
		return ['success' => true, 'leader' => $res,];
	}

}
