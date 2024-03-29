<?php
namespace App\Http\Controllers;

use App\Document;
use Illuminate\Http\Request;


class WelcomeController extends AppBaseController{


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        $user = $request->user();
        $letter_alias = config('constants.DOC_TYPES.LETTER');
        $leave_rqs = config('constants.DOC_TYPES.LEAVE_REQUESTS');
        $documents = Document::whereIn("category",[$letter_alias,$leave_rqs])->where("created_by",$user->id)->paginate(10);
        return view('new_home',compact('documents'));
    }

    public function search(Request $request)
    {
        // Retrieve the search query and category from the request
        $query = $request->input('query');
        $category = $request->input('category');
    
        // Start with querying all documents
        $documents = Document::query();
    
        // Apply filters if they are provided
        if ($query) {
            $documents->where(function($q) use ($query) {
                $q->where('category', 'like', "%$query%")
                  ->orWhere('status', 'like', "%$query%")
                  ->orWhere('created_at', 'like', "%$query%");
            });
        }
    
        if ($category) {
            $documents->where('category', $category);
        }
    
        // Paginate the filtered documents
        $documents = $documents->paginate(10);
    
        // Return the view with the filtered documents
        return view('new_home', compact('documents'));
    }
    

    public function welcome()
    {
        \Artisan::call("inspire");
        $quotes = \Artisan::output();
        return view('welcome',compact('quotes'));
    }

}