<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use DB;
use Illuminate\Support\Arr;
use App\Models\Score;

class HomeController extends Controller {

    public function index() {
        return view('home');
    }

    public function user(Request $request){
    	$cardArr = [
			'2' => 10, 
			'3' => 20, 
			'4' => 30, 
			'5' => 40, 
			'6' => 50, 
			'7' => 60, 
			'8' => 70, 
			'9' => 80, 
			'10' => 90,
			'J' => 100, 
			'Q' => 110, 
			'K' => 120, 
			'A' => 130, 
		];
    	$cards   = array_keys($cardArr); 

      	$validator = Validator::make($request->all(), [
            'user_name'    => 'required|max:55',
            'user_cards'   => 'required|array|min:1|max:13', //its an array and count of array should be 1 to 13
            'user_cards.*' => 'required|in:' . implode(',', $cards).'|string|distinct', // elments of array should be unique
        ]);

        if ($validator->fails()) { return response()->json(['errors' => $validator->errors()->all()],422); }

        try{
        	$machine_cards = Arr::random($cards,count($request->user_cards));
        	
 			/*$score = array_map(function ($arr1_item,$arr2_item) use ($cardArr) {
        		return [
    				'u' => $cardArr[$arr1_item]>$cardArr[$arr2_item] ? 1 : 0,
    				'm' => $cardArr[$arr2_item]>$cardArr[$arr1_item] ? 1 : 0
        		];
		    }, $request->user_cards, $machine_cards);
		    $user_score = array_sum(array_map(function($item) { return $item['u']; }, $score));
		    $machine_score = array_sum(array_map(function($item) { return $item['m']; }, $score));*/


	    	$user_score = $machine_score = 0;
			foreach($request->user_cards as $key => $v1) {
				$v2 = $machine_cards[$key];
				$user_score += ($cardArr[$v1]>$cardArr[$v2] ? 1 : 0);
				$machine_score += ($cardArr[$v2]>$cardArr[$v1] ? 1 : 0);
			}
		    
            DB::beginTransaction();

            $user = User::firstOrCreate(['user_name'=>$request->user_name]);
            if(empty($user->id)) throw new \Exception("Error Processing Request", 1);
         	
         	$res = Score::Create([
         		'user_id'       => $user->id, 
         		'user_score'    => $user_score, 
         		'machine_score' => $machine_score, 
         		'user_won'      => ($user_score > $machine_score) ? true : false
         	]);
         	if(empty($res->id)) throw new \Exception("Error Processing Request", 2);

            DB::commit();
            return response()->json(['success' => 'User created successfully!',
            	'user'          => $user, 
            	'machine_cards' => $machine_cards,
            	'user_score'    => $user_score,
            	'machine_score' => $machine_score,
            ],200);
        }catch (\Exception $e){

            DB::rollBack();
            return response()->json(['errors' =>[$e->getMessage()." #".$e->getLine()]],422);
        }
    }

	public function leader(){
		DB::select("SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
		$res = Score::groupBy('user_id')->orderBy(DB::raw('count(user_won=1)'),'desc')
					->join('users','users.id','=','scores.user_id')
					->get(['users.user_name',DB::raw('count(*) as total_games'),DB::raw('count(user_won=1) as total_won')]);
		return response()->json(['success' => true, 'leader' => $res,],200);
	}
}
