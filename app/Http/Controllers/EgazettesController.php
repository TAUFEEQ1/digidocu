<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Egazette;
use App\FileType;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use mikehaertl\pdftk\Pdf;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;

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
            if(!$user->is_subscribed){
                $documents = [];
                return view("egazettes.index",compact("documents"));
            }
            
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
        $password = Str::random(30);
        $egazette->gaz_passkey = $password; 
        $egazette->save();
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
        $file_type = FileType::where('name', "Gazette")->first();
        $fileData['file_type_id'] = $file_type->id;
        $fileData['document_id'] = $egazette->id;
        $egazette->files()->insert([$fileData]);
        return redirect()->route('egazettes.index');
    }

    /**
     * Display the specified resource.
     */
    public function view(int $id)
    {
        $egazette = Egazette::find($id);
        $files = $egazette->files;
        $file = $files->first();
        $filePath = storage_path('app/files/original/' . $file->file);
    
        // If the file is password-protected, decrypt it temporarily before serving
        if ($egazette->gaz_passkey) {
            $tempDecryptedFilePath = tempnam(sys_get_temp_dir(), 'decrypted_pdf_');
            Artisan::call('app:decrypt-pdf', [
                'inputFile' => $filePath,
                'outputFile' => $tempDecryptedFilePath,
                'userPassword' => $egazette->gaz_passkey
            ]);
            $filePath = $tempDecryptedFilePath;
        }
    
        return response()->download($filePath, $file->name)->deleteFileAfterSend(true);
    }
    public function show(int $id){
        $document = Egazette::find($id);
        $signedUrl = URL::temporarySignedRoute('egazettes.view', now()->addSeconds(30), ['id' => $id]);
        return view("egazettes.show",compact("document","signedUrl"));
    }

    public function download(int $id){
        $egazette = Egazette::find($id);
        $files = $egazette->files;
        $file = $files->first();
        $filePath = storage_path('app/files/original/' . $file->file);
        return response()->download($filePath, $file->name);
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
