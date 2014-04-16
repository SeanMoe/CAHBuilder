<?php

class CardsController extends BaseController {

	public function __construct(){
		$this->beforeFilter('csrf',array('on'=>'post'));
	}

	public function getLogin(){
		if(!Auth::check()){
			return View::make('cards/login');
		} else {
			return Redirect::to('index');
		}
	}

	public function postLogin(){
		$rules = array(
			'email' => 'required|email',
			'password'=>'required|alphaNum|min:3'
			);

		$validator = Validator::make(Input::all(), $rules);
		if($validator->fails()){
			return Redirect::to('login')
				->withErrors($validator)
				->withInput(Input::except('password'));
		} else {
			$userdata = array(
				'email'=>Input::get('email'),
				'password'=>Input::get('password')
				);
			if (Auth::attempt($userdata)){
				return Redirect::to('index');
			} else {
				return Redirect::to('login')
				->withErrors(array('password'=>'Invalid username or password'))
				->withInput(Input::except('password'));
			}
		}
	}

	public function getLogout(){
		Auth::logout();
		return Redirect::to('index');
	}

	/**
	 * Display a listing of the cards.
	 *
	 * @return Response
	 */
	public function getIndex()
	{

		$black['count'] = DB::table('cards')->where('color','Black')->count();
		$white['count'] = DB::table('cards')->where('color','White')->count();

		$white['cards'] = Cards::orderBy(DB::raw('RAND()'))->where('color','White')->take(10)->get();
		$black['cards'] = Cards::orderBy(DB::raw('RAND()'))->where('color','Black')->take(1)->get();

		$cards['black'] = $black;
		$cards['white'] = $white;

		return View::make('cards/index',$cards);
	}

	/**
	 * Show the form for creating a new card.
	 *
	 * @return Response
	 */
	public function getCreate($color)
	{
		if($color == "White" || $color == "Black"){
			return View::make('cards/create')->with('color',$color);
		} else {
			$cards = Cards::all();
			return Redirect::to('cards')->with('message','Please select a valid color!')->with('cards',$cards);
		}
	}

	public function postCreate()
	{
		$validator = Validator::make(Input::all(), Cards::$rules);
			if($validator->passes()){
			$card = new Cards;
			$card->color = Input::get('color');
			$card->text = Input::get('cardText');
			$card->createdBy = Input::get('name');
			$card->save();

			return Redirect::to('cards')->with('message','Thanks for creating a card!');
		} else {
			$color = Input::get('color');
			return Redirect::to('cards/create/'.$color)->with('message','The following errors occured')->withErrors($validator)->withInput();
		}
	}

	public function postVote()
	{
		$vote = Input::get('vote');
		$id = Input::get('id');
		$card = Cards::find($id);
		$vote == 'up' ? $card->increment('votes') : $card->decrement('votes');
	}

}