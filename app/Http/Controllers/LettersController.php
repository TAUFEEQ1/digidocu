<?php

namespace App\Http\Controllers;

use App\Letter;
use App\User;
use App\File;
use App\FileType;
use Illuminate\Http\Request;
use App\Letter as GlobalLetter;

class LettersController extends AppBaseController
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        /** @var \App\User */
        $user = $request->user();
        $baseQ = GlobalLetter::with(['createdBy', 'executedBy', 'managedBy', 'assignedTo']);
        if ($user->is_registry_member) {
            $baseQ->where("created_by", $user->id);
        } elseif ($user->is_executive_secretary) {
            $baseQ->where("executed_by", $user->id)->orWhere("executed_by", null);
        } elseif ($user->is_managing_director) {
            $baseQ->where("managed_by", $user->id)->orWhere("managed_by", null);
        } else {
            $baseQ->where("assigned_to", $user->id);
        }
        $documents = $baseQ->paginate(10);
        return view("letters.index", compact("documents","user"));
    }
    public function create(Request $request)
    {
        $this->authorize('scan_letters', User::class);
        return view("letters.create");
    }
    public function store(Request $request)
    {
        $this->authorize('scan_letters', User::class);
        $request->validate([
            'file_scan' => 'required|file|mimes:pdf|max:10240', // Max file size: 10MB
            'sender' => 'required|string',
            'sending_entity' => 'required|string',
            'description' => 'required|string',
            'subject' => 'required|string'
        ]);
        $user = $request->user();
        // save document.
        $data = $request->all();
        $letter = GlobalLetter::create([
            'name' => $data['subject'],
            'sender' => $data['sender'],
            'subject' => $data['subject'],
            'sending_entity' => $data['sending_entity'],
            'description' => $data['description'],
            'status' => config('constants.LETTER_STATES.SUBMITTED'),
            'created_by' => $user->id,
            'category' => config('constants.DOC_TYPES.LETTER')
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
        $file_type = FileType::where('name', config('constants.DOC_TYPES.LETTER'))->first();
        $fileData['file_type_id'] = $file_type->id;
        $fileData['document_id'] = $letter->id;

        $letter->files()->insert([$fileData]);
        return redirect()->route('letters.index');
    }

    public function editStatus(int $id, Request $request)
    {
        /** @var \App\User */
        $user = $request->user();
        $action = $request->input('action');

        if ($action == config('constants.LETTER_STATES.EXECUTED')) {
            $this->authorize('execute_letters', User::class);
            $letter = GlobalLetter::where("status", config('constants.LETTER_STATES.SUBMITTED'))->findOrFail($id);
            $letter->executed_by = $user->id;
            $letter->status = config('constants.LETTER_STATES.EXECUTED');
            $letter->newActivity('Letter executed by secretary - ' . $user->name);
            $letter->save();
        } elseif ($action == config('constants.LETTER_STATES.DISCARDED')) {
            $this->authorize('discard_letters', User::class);
            if ($user->is_executive_secretary) {
                $letter = GlobalLetter::where("status", config('constants.LETTER_STATES.SUBMITTED'))->findOrFail($id);
                $letter->lt_executor_notes = $request->input('vcomment', 'NA');
                $letter->executed_by = $user->id;
                $letter->newActivity('Letter discarded by secretary - ' . $user->name);
            } else if ($user->is_managing_director) {
                $letter = GlobalLetter::where("status", config('constants.LETTER_STATES.EXECUTED'))->findOrFail($id);
                $letter->lt_manager_notes = $request->input('vcomment', 'NA');
                $letter->managed_by = $user->id;
                $letter->newActivity('Letter discarded by manager-' . $user->name);
            }
            $letter->status = config('constants.LETTER_STATES.DISCARDED');
            $letter->save();
        } else if ($action == config('constants.LETTER_STATES.MANAGED')) {
            $this->authorize('manage_letters', User::class);
            $letter = GlobalLetter::where("status", config('constants.LETTER_STATES.EXECUTED'))->findOrFail($id);
            $letter->lt_manager_notes = $request->input('vcomment', 'NA');
            $letter->managed_by = $user->id;
            $letter->status = config('constants.LETTER_STATES.MANAGED');
            $letter->newActivity('Letter managed by manager-' . $user->name);
            $letter->save();
        }

        return redirect()->route('letters.show', ['id' => $id]);
    }

    public function discard(int $id, Request $request)
    {
        $this->authorize('discard_letters', User::class);
        $letter = GlobalLetter::where("status", config('constants.LETTER_STATES.SUBMITTED'))->findOrFail($id);
        $letter->status = config('constants.LETTER_STATES.DISCARDED');
        $letter->save();
        return redirect()->route('letters.show', ['id' => $id]);
    }

    public function show(int $id, Request $request)
    {
        $document = GlobalLetter::with(['createdBy', 'executedBy', 'managedBy', 'assignedTo'])->findOrFail($id);
        $user = $request->user();
        return view("letters.show", compact("document", "user"));
    }
}
