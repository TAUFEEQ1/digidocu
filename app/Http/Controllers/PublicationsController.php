<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Publication;
use App\PublicationBuyer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Artisan;
use App\FileType;
use App\GovPayApi;
use App\Jobs\PublicationPayment;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;

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

        $documents = $baseQ->orderBy('documents.created_at', 'desc')->paginate(15);
    
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

    public function buy(int $id, Request $request)
    {
        $user = $request->user();
        $publication = Publication::findOrFail($id);
        $networks = config("constants.MOBILE_NETWORKS");
    
        $payment_type = $request->input('payment_type');
    
        // Validate request based on payment type
        $validationRules = [
            'payment_type' => 'required|string|in:MOBILE,CARD',
        ];
    
        if ($payment_type === 'MOBILE') {
            $validationRules['mobile_network'] = 'required|integer|in:' . implode(',', array_keys($networks));
            $validationRules['mobile_no'] = 'required|string|regex:/^0[0-9]{9}$/';
        }
    
        $request->validate($validationRules);
    
        // Create the publication buyer data array
        $publication_buyer_data = [
            "publication_id" => $publication->id,
            "buyer_id" => $user->id,
            "status" => config('constants.ADVERT_STATES.PENDING PAYMENT'),
            "payment_type" => $payment_type,
            "payment_ref" => "NA"
        ];
    
        if ($payment_type === 'MOBILE') {
            $publication_buyer_data["mobile_network"] = $networks[(int)$request->input("mobile_network")];
            $publication_buyer_data["mobile_no"] = $request->input("mobile_no");
    
            /** @var \App\PublicationBuyer */
            $publication_buyer = PublicationBuyer::create($publication_buyer_data);
            PublicationPayment::dispatch($publication_buyer);
            $publication_buyer->newActivity("Publication purchase initiated by " . $user->name);
            $publication_buyer->save();
    
            return redirect()->route("publications.show", ["publication" => $id]);
        } elseif ($payment_type === 'CARD') {
            /** @var \App\PublicationBuyer */
            $publication_buyer = PublicationBuyer::create($publication_buyer_data);
            $redirect_url = url()->route('publications.show', $publication->id);
            $pay = new GovPayApi([
                "PAY_TYPE" => "CARD",
                "email" => $user->email,
                "name" => $user->name,
                "redirect_url" => $redirect_url
            ]);
            $ref = $pay->initialize();
            $payment_url = $pay->getPaymentUrl();
            $publication_buyer->payment_ref = $ref;
            $publication_buyer->newActivity("Publication purchase initiated by " . $user->name);
            $publication_buyer->save();
    
            return redirect($payment_url);
        }
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
            "category"=>config('constants.DOC_TYPES.PUBLICATION'),
            "pub_isbn"=>$request->input("pub_isbn")
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
    public function show(int $publication, Request $request)
    {
        //
        $document = Publication::find($publication);
        $user = $request->user();
        $signedUrl = URL::temporarySignedRoute('publications.view', now()->addSeconds(30), ['id' => $publication]);
        return view("publications.show",compact("document","user","signedUrl"));
    }

    public function download(int $id){
        $egazette = Publication::find($id);
        $files = $egazette->files;
        $file = $files->first();
        $filePath = storage_path('app/files/original/' . $file->file);
        return response()->download($filePath, $file->name);
    }

    public function view(int $id){
        $egazette = Publication::find($id);
        $files = $egazette->files;
        $file = $files->first();
        $filePath = storage_path('app/files/original/' . $file->file);
    
        // If the file is password-protected, decrypt it temporarily before serving
        if ($egazette->pub_key) {
            $tempDecryptedFilePath = tempnam(sys_get_temp_dir(), 'decrypted_pdf_');
            Artisan::call('app:decrypt-pdf', [
                'inputFile' => $filePath,
                'outputFile' => $tempDecryptedFilePath,
                'userPassword' => $egazette->pub_key
            ]);
            $filePath = $tempDecryptedFilePath;
        }
    
        return response()->download($filePath, $file->name)->deleteFileAfterSend(true);
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
