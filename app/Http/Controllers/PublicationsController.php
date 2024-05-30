<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Publication;
use App\PublicationBuyer;
use Illuminate\Support\Facades\DB;

class PublicationsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $baseQ = Publication::query();
        $user = $request->user();

        if ($request->has('query')) {
            $searchQuery = $request->input('query');
            $baseQ->where('pub_title', 'LIKE', '%' . $searchQuery . '%');
        }

        if($user->is_client){
            $baseQ->leftJoin('publication_buyers',function($join) use ($user){
                $join->on('documents.id', '=', 'publication_buyers.publication_id')
                ->where('publication_buyers.buyer_id', '=', $user->id)
                ->where('publication_buyers.status', '=',config('constants.ADVERT_STATES.PAID'))
                ->select('documents.*', DB::raw('CASE WHEN publication_buyers.id IS NOT NULL THEN TRUE ELSE FALSE END AS bought'));
            });
        }

        $documents = $baseQ->orderBy('created_at', 'desc')->paginate(15);
    
        return view('publications.index', compact('documents','user'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
