<?php namespace admin;

class TutorsController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /tutors
	 *
	 * @return Response
	 */
	public function index()
	{
		$users = \User::getTutors()->paginate(10);
		return \View::make('admin.tutors.index',compact('users'));
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /tutors/create
	 *
	 * @return Response
	 */
	public function create()
	{
		return \View::make('admin.tutors.create');
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /tutors
	 *
	 * @return Response
	 */
	public function store()
	{		
		if (\User::createTutor(\Input::all())) {
			\Session::flash('success', 'Tutor Creador exitósamente');
			return \Redirect::route('admin.tutors.index');
		}else{
			\Session::flash('error', 'Ocurrió un error. Valida los datos.');
			return \View::make('admin.tutors.create');
		}
	}

	/**
	 * Display the specified resource.
	 * GET /tutors/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$user = \User::find($id);
		return \View::make('admin.tutors.show', compact('user'));
	}

	/**
	 * Show the form for editing the specified resource.
	 * GET /tutors/{id}/edit
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$user = \Sentry::findUserById($id);
		return \View::make('admin.tutors.edit', compact('user'));
	}

	/**
	 * Update the specified resource in storage.
	 * PUT /tutors/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$user = \Sentry::findUserById($id);
		if (\User::updateAttributes($user,\Input::all())) {
			\Session::flash('success', 'Tutor actualizado exitósamente');
			return \Redirect::route('admin.tutors.index');
		}else{
			\Session::flash('error', 'Ocurrió un error. Valida los datos.');
			return \Redirect::route('admin.tutors.edit', $id);
		}

	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /tutors/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		try{
			$user = \Sentry::findUserById($id);
		  $user->delete();
		  \Session::flash('success', 'Tutor eliminado exitósamente');
		}
		catch (\Cartalyst\Sentry\Users\UserNotFoundException $e){
			\Session::flash('error', 'Tutor no encontrado');
		}
		finally{
			return \Redirect::route('admin.tutors.index');
		}
	}

}