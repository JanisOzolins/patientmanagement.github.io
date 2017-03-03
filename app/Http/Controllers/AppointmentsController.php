<?php

namespace App\Http\Controllers;

use App\User;
use App\Appointment;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class AppointmentsController extends Controller
{
    
	public function index() 
	{
		$users = User::all();
		return view('appointments.index')->with('users', $users);
	}

	public function create() 
	{
		$users = User::all();
		return view('appointments.create')->with('users', $users);
	}

	public function store() 
	{
		// find the user
		$user = User::find(request('a_patient_id'));

		// add embedded 'Appointment' instance
		$appointment = $user->appointments()->create(['a_patient' => $user->first_name . ' ' . $user->last_name, 'a_date' => request('a_date'), 'a_time' => request('a_time'), 'a_details' => request('a_details')]);

		// save changes
		$user->save();

		// redirect to /appointments page
		return redirect('/appointments');
	}

	public function delete($uid, $aid) {

		// find the patient
		$user = User::find($uid);

		// find the appointment
		$appointment = $user->appointments()->find($aid);

		// delete the appointment
		$appointment->delete();

		// save changes
		$user->save();

		// redirect to /appointments page
		return Redirect::route('appointments.index');

	}

	// public function show($uid, $aid) {
	// 	$user = User::find($uid);
	// 	$appointment = $user->appointments()->where('id', $aid)->first();

	// 	return view('appointments.show')->with('appointment', $appointment);

	// }

	public function edit($uid, $aid) {

		//find the user
		$user = User::find($uid);

		// find the appointment
		$appointment = $user->appointments()->find($aid);

		// return variables to the "edit" view
		return view('appointments.edit')->with('appointment', $appointment);

	}

	public function update($uid, $aid) {

		// find the user
		$user = User::find($uid);

		// find the appointment
		$appointment = $user->appointments()->find($aid);

		// update data
		$appointment->a_time = request('a_time');
		$appointment->a_date = request('a_date');
		$appointment->a_details = request('a_details');

		$appointment->save();

		return Redirect::route('appointments.index');
 	}
}
