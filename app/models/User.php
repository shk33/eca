<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'remember_token');

	public static function getTutors()
	{
		return User::getTutorGroup()->users();
	}

	public static function getStudents()
	{
		return User::getStudentGroup()->users();
	}

	public static function createTutor($userData)
	{
	  if ($user = User::createUser($userData)) {
	  	$tutorGroup = User::getTutorGroup();
	  	$user->addGroup($tutorGroup);
	  	return $user;
	  }else{
	  	return false;
	  }
	}

	public static function createStudent($userData)
	{
	  if ($user = User::createUser($userData)) {
	  	$studentGroup = User::getStudentGroup();
	  	$user->addGroup($studentGroup);
	  	return $user;
	  }else{
	  	return false;
	  }
	}

	public static function updateAttributes($user,$userData)
	{
    $user->first_name = $userData['first_name'];
    $user->last_name  = $userData['last_name'];
    $user->username   = $userData['username'];
    $user->email      = $userData['email'];
    $user->enrollment_number = $userData['enrollment_number'];
    
    if ($userData['password'] && !empty(trim($userData['password']))) {
    	$user->password = $userData['password'];
    }
    try {
	    if ($user->save()){
	    	return $user;
	    }
	    else{
	    	return false;
	    }
    } catch (Exception $e) {
	   		return false;
    }
	}

	public function scholarGroups()
  {
    return $this->hasMany('ScholarGroup');
  }

  public function attendingGroups()
  {
  	return $this->belongsToMany('ScholarGroup', 'scholar_groups_users', 'user_id', 'scholar_group_id');
  }

  public function getScholarGroupsPaginated()
{
    return $this->scholarGroups()->paginate(10);
}

	############################
	# Private Functions
	############################
	private static function createUser($userData)
	{
		try{
	    $user = Sentry::register(array(
	      'first_name' => $userData['first_name'],
	      'last_name'  => $userData['last_name'],
	      'username'   => $userData['username'],
	      'enrollment_number' => $userData['enrollment_number'],
	      'email'      => $userData['email'],
	      'password'   => $userData['password'],
	      'activated'  => true,
	    ));

	    return $user;
		}
		catch (Exception $e)
		{
			return false;
		}
	}

	private static function getTutorGroup()
	{
		return Sentry::findGroupByName("Tutors");
	}

	private static function getStudentGroup()
	{
		return Sentry::findGroupByName("Students");
	}

}
