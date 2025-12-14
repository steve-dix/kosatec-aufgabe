<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\notizen;

class NotizensController extends Controller
{
	public function getAll()
	{
		$notizen = notizen::orderBy('erstellungsdatum')->get();
		return response()->json($notizen);
	}

	public function getPage(Request $request)
	{
		$notizen = notizen::orderBy('erstellungsdatum')->skip(($request->page-1)*12)->take(12)->get(); 
		return response()->json($notizen);
	}
	
	public function getCount()
	{
		$count = notizen::count();
		return response()->json($count);
	}
	
	public function getNotiz($id)
	{
		$notiz = notizen::find($id);
		if(!empty($notiz)) {
			return response()->json($notiz);
		}
		return response()->json(['error' => 'Notiz nicht gefunden'],201);
	}
	
	public function updateNotiz(Request $request,$id)
	{
		if(notizen::where('id',$id)->exists()) {
			$notiz = notizen::find($id);
			//do saving it
			$notiz->save();
			return response()->json(['message'=>'success']);
		}
		return response()->json(['error' => 'Notiz nicht gefunden'],201);
	}
	
	public function createNotiz(Request $request)
	{
		try {
			$validatedData = $request->validate(
			[	
				'titel' => ['required', 'max:30'],
				'inhalt' => ['required', 'max:300'],
				'erstellungsdatum' => ['required'], //Rule::date()->format('d.m.Y')],
				'wichtig' => ['required']
			]);
		
	
			// Create a new record
			$notiz = notizen::create([
				'titel'=>$request->titel, 
				'inhalt'=>$request->inhalt,
				'erstellungsdatum'=>date('Y-m-d',strtotime($request->erstellungsdatum)),
				'wichtig'=>$request->wichtig
				]);

			return response()->json([	
				'success' => true,
				'notiz' => $notiz
			], 201);
		} catch (ValidationException $e) {
			$errors = $e->errors();
			return response()->json(['success'=> false, 'errors' => $errors], 422);
		}
		
		
	}
	
	public function deleteNotiz(Request $request)
	{
		if(notizen::where('id',$id)->exists()) {
			$notiz->delete($id);
			return response()->json(['message'=>'success']);
		}
		return response()->json(['error' => 'Notiz nicht gefunden'],201);
	}
}
