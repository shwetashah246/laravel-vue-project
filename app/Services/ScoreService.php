<?php

namespace App\Services;

use Illuminate\Support\Facades\Validator;
use DB;
use Illuminate\Support\Arr;
use App\Models\User;
use App\Models\Score;

class ScoreService  {

   	protected $cardArr = [];
	protected $cards   = [];

	public function __construct() {
        $this->cardArr = [
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
		$this->cards = array_keys($this->cardArr);
    }

	public function validateUserData($input) {
		$validator = Validator::make($input, [
			'user_name'    => 'required|max:55',
			'user_cards'   => 'required|array|min:1|max:13', //its an array and count of array should be 1 to 13
			'user_cards.*' => 'required|in:' . implode(',', $this->cards).'|string|distinct', // elments of array should be unique
		]);

		if ($validator->fails()) { return ['errors' => $validator->errors()->all()]; }
	}

	public function scores($input){
		
		$result = $this->validateUserData($input);
        if(!empty($result['errors'])) { return $result['errors']; }

		try{

			DB::beginTransaction();

			$machine_cards = Arr::shuffle(Arr::random($this->cards,count($input['user_cards'])));

			$user_score = $machine_score = 0;
			foreach($input['user_cards'] as $key => $v1) {
				$v2 = $machine_cards[$key];
				$user_score += ($this->cardArr[$v1]>$this->cardArr[$v2] ? 1 : 0);
				$machine_score += ($this->cardArr[$v2]>$this->cardArr[$v1] ? 1 : 0);
			}

			$user = User::firstOrCreate(['user_name'=>$input['user_name']]);
			if(empty($user->id)) throw new \Exception("Error Processing Request", 1);
			
			$res = Score::Create([
				'user_id'       => $user->id, 
				'user_score'    => $user_score, 
				'machine_score' => $machine_score, 
				'user_won'      => ($user_score > $machine_score) ? true : false
			]);
			if(empty($res->id)) throw new \Exception("Error Processing Request", 2);

			DB::commit();
			return [
				'success'       => true,
				'user'          => $user, 
				'machine_cards' => $machine_cards,
				'user_score'    => $user_score,
				'machine_score' => $machine_score,
			];
		}catch (\Exception $e){

			DB::rollBack();
			return ['errors' =>[$e->getMessage()." #".$e->getLine()]];
		}
	}

}
