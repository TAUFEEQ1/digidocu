<?php
namespace App\Http\Controllers;

use App\Letter;
use App\User;
use App\File;
use App\FileType;
use Illuminate\Http\Request;
use App\Letter as GlobalLetter;

class LettersController extends AppBaseController{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request){
        $user = $request->user();
        $documents = GlobalLetter::with(['createdBy','executedBy','managedBy','assignedTo'])->where(function ($query) use ($user) {
            $query->where('created_by', $user->id)
                ->orWhere('executed_by', $user->id)
                ->orWhere('managed_by', $user->id)
                ->orWhere('assigned_to', $user->id);
        })
        ->paginate(10);
        return view("letters.index",compact("documents"));
    }
    public function create(Request $request){
        $this->authorize('scan_letters',User::class);
        return view("letters.create");
    }
    public function store(Request $request){
        $this->authorize('scan_letters',User::class);
        $request->validate([
            'file_scan' => 'required|file|mimes:pdf|max:10240', // Max file size: 10MB
            'sender'=>'required|string',
            'sending_entity'=>'required|string',
            'description'=>'required|string',
            'subject'=>'required|string'
        ]);
        $user = $request->user();
        // save document.
        $data = $request->all();
        $letter = GlobalLetter::create([
            'name'=>$data['subject'],
            'sender'=>$data['sender'],
            'subject'=>$data['subject'],
            'sending_entity'=>$data['sending_entity'],
            'description'=>$data['description'],
            'status'=>config('constants.LETTER_STATES.SUBMITTED'),
            'created_by'=>$user->id,
            'category'=>config('constants.DOC_TYPES.LETTER')
        ]);
        $letter->newActivity('Letter Submitted');
        $letter->save();

        // save file.
        $uploaded_file = $request->file('file_scan');
        $uploaded_file->store('files/original');
        $fileData['name'] =  $uploaded_file->getClientOriginalName();
        $fileData['created_by'] = $user->id;
        $fileData['file'] = $uploaded_file->hashName();
        $fileData['custom_fields'] = json_encode([]);
        $fileData['created_at'] = now();
        $fileData['updated_at'] = now();
        $file_type = FileType::where('name',config('constants.DOC_TYPES.LETTER'))->first();
        $fileData['file_type_id'] = $file_type->id;
        $fileData['document_id'] = $letter->id;

        $letter->files()->insert([$fileData]);
        return redirect()->route('letters.index');
    }
    public function show(int $id,Request $request){
        $document = GlobalLetter::with(['createdBy','executedBy','managedBy','assignedTo'])->find($id);
        $user = $request->user();
        return view("letters.show",compact("document","user"));
    }
}