<?php

namespace App\Http\Controllers;

use App\Egazette;
use Illuminate\Http\Request;
use App\FileType;

class EgazetteSupplementsController extends Controller
{
    //
    public function index(int $id,Request $request){
        $document = Egazette::with('supplements')->find($id);
        return view("egazettes.supplements",compact("document"));
    }

    public function store(int $id, Request $request){
        $this->authorize("create egazatte");
        $user = $request->user();
        $egazette = Egazette::find($id);
        foreach ($request->file('file_scan') as $file) {
            $file->store('supplements');
            $fileData['name'] = $file->getClientOriginalName();
            $fileData['created_by'] = $user->id;
            $fileData['file'] = $file->getClientOriginalName();
            $fileData['custom_fields'] = json_encode([]);
            $fileData['created_at'] = now();
            $fileData['updated_at'] = now();
            $file_type = FileType::where('name', "Supplement")->first();
            $fileData['file_type_id'] = $file_type->id;
            $fileData['document_id'] = $egazette->id;
            $egazette->files()->insert([$fileData]);
        }
        return redirect()->route('egazettes.supplements',["id"=>$egazette->id]);
    }

}
