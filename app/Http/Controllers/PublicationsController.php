<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Publication;
use App\PublicationBuyer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Artisan;
use App\FileType;

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
        return view('publications.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $this->authorize('create egazette');
        $user = $request->user();
        $publication = Publication::create([
            "pub_title"=>$request->input("pub_title"),
            "pub_fees"=>$request->input("pub_fees"),
            "pub_author"=>$request->input("pub_author"),
            "created_by"=>$user->id,
            "name"=>$request->input("pub_title"),
            "status"=>config('constants.GAZETTE_STATUSES.PUBLISHED'),
            "category"=>config('constants.DOC_TYPES.PUBLICATION')
        ]);
        $publication->newActivity('Publication uploaded by: '.$user->name);
        
        $password = Str::random(30);
        $publication->pub_key = $password; 
        $publication->save();
        // save file.
        $uploaded_file = $request->file('file_scan');
        $stored_path = $uploaded_file->store('files/original');

        // Get the full path
        $full_path = storage_path('app/' . $stored_path);

        
        $new_name = Str::random(50).".pdf";
        $result = str_replace($uploaded_file->hashName(),$new_name,$full_path);
        Artisan::call('app:encrypt-pdf',[
            'inputFile' => $full_path,
            'outputFile' => $result,
            'userPassword' => $password
        ]);

        $fileData['name'] =  $uploaded_file->getClientOriginalName();
        $fileData['created_by'] = $user->id;
        $fileData['file'] = $new_name;
        $fileData['custom_fields'] = json_encode([]);
        $fileData['created_at'] = now();
        $fileData['updated_at'] = now();
        $file_type = FileType::where('name', "Publication")->first();
        $fileData['file_type_id'] = $file_type->id;
        $fileData['document_id'] = $publication->id;
        $publication->files()->insert([$fileData]);
        return redirect()->route('publications.index');
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
