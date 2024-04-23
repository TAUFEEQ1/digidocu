<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Egazette;

class EgazettesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $user = $request->user();
        $baseQ = Egazette::query();
        if($user->is_client){
            // determine sub_start and end
            /** @var \App\Subscription */
            $subscription = $user->subscriptions->where('status', config('constants.SUB_STATUSES.ACTIVE'))->first();
            if(!$subscription){
                return [];
            }
            $baseQ->where('gaz_published_on','<',$subscription->sub_end_date);
        }

        if($request->has('query')){
            $search_term = $request->input('query');
            $baseQ->where('gaz_issue_no',$search_term);
        }
        $documents = $baseQ->paginate(10);
        return view("egazettes.index",compact("documents"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        //
        $this->authorize('create egazette');
        $user = $request->user();
        return view("egazettes.create",compact("user"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $this->authorize('create egazette');
        $user = $request->user();
        $egazette = Egazette::create([
            "gaz_published_on"=>$request->input('gaz_published_on'),
            "gaz_issue_no"=>$request->input("gaz_issue_no"),
            "gaz_sub_category"=>$request->input("gaz_sub_category"),
            "created_by"=>$user->id,
            "name"=>"Uganda Gazette issued ".$request->input("gaz_issue_no"),
            "status"=>config('constants.GAZETTE_STATUSES.PUBLISHED'),
            "category"=>config('constants.DOC_TYPES.EGAZETTE')
        ]);
        $egazette->newActivity('Gazette uploaded by: '.$user->name);
        $egazette->save();
        return redirect()->route('egazettes.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
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
