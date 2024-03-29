<?php
namespace App\Http\Controllers;

use App\Letter;
use Illuminate\Http\Request;
use Letter as GlobalLetter;

class LettersController extends AppBaseController{

    public function index(Request $request){
        $user = $request->user();

        $documents = GlobalLetter::where(function ($query) use ($user) {
            $query->where('created_by', $user->id)
                ->orWhere('executed_by', $user->id)
                ->orWhere('managed_by', $user->id)
                ->orWhere('assigned_to', $user->id);
        })
        ->paginate(10);
        return view("letters",compact("documents"));
    }
}