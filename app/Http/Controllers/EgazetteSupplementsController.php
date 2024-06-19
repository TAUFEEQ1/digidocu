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
        $user = $request->user();
        return view("egazettes.supplements",compact("document","user"));
    }

    public function store(int $id, Request $request){
        $this->authorize("create egazette");
        $user = $request->user();
        $egazette = Egazette::find($id);
        foreach ($request->file('file_scan') as $file) {
            $file->store('files/original');
            $fileData['name'] = $file->getClientOriginalName();
            $fileData['created_by'] = $user->id;
            $fileData['file'] = $file->hashName();
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
