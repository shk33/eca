<?php

class ScholarGroup extends \Eloquent {
	// Add your validation rules here
  public static $rules = [
    'name' => 'required',
  ];

  // Don't forget to fill this array
  protected $fillable = ['id', 'name', 'description', 'user_id'];
}