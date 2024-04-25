<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Advert;
use App\FileType;
use App\Jobs\AdvertPayment;

class AdvertsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $user = $request->user();
        $baseQ = Advert::query();
        if ($user->is_client) {
            $baseQ->where("created_by", $user->id);
        }else if($user->is_registrar){
            $baseQ->where('ad_registrar_id',$user->id)->orWhereNull('ad_registrar_id');
        }else{
            $documents = Advert::where("id",0)->get();
            return view("adverts.index", compact("documents", "user"));
        }
        $documents = $baseQ->orderBy('created_at', 'desc')->paginate(15);
        return view("adverts.index", compact("documents", "user"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        //
        $user = $request->user();
        return view("adverts.create", compact("user"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $user = $request->user();
        $service = config("constants.ADVERT_SERVICES")[(int)$request->input('ad_category')];
        $advert = Advert::create(
            [
                "name" => "Advert application by " . $user->name,
                "description" => $request->input("description"),
                "category" => config("constants.DOC_TYPES.ADVERT"),
                "status" => config('constants.ADVERT_STATES.PENDING PAYMENT'),
                "ad_category" => $service['name'],
                "ad_amount" => $service['price'],
                "ad_payment_method" => $request->input("ad_payment_method"),
                "ad_payment_mobile_network" => $request->input("ad_payment_mobile_network"),
                "ad_subtitle" => $request->input('ad_subtitle'),
                "created_by"=>$user->id
            ]
        );
        $advert->newActivity('Advert Submitted by: '.$user->name);
        $uploaded_file = $request->file('file_scan');
        $uploaded_file->store('files/original');

        $fileData['name'] =  $uploaded_file->getClientOriginalName();
        $fileData['created_by'] = $user->id;
        $fileData['file'] = $uploaded_file->hashName();
        $fileData['custom_fields'] = json_encode([]);
        $fileData['created_at'] = now();
        $fileData['updated_at'] = now();
        $file_type = FileType::where('name', "Advert")->first();
        $fileData['file_type_id'] = $file_type->id;
        $fileData['document_id'] = $advert->id;
        $advert->files()->insert([$fileData]);
        $advert->save();
        AdvertPayment::dispatch($advert);
        return redirect()->route("adverts.index");
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id,Request $request)
    {
        //
        $user = $request->user();
        $document = Advert::find($id);
        return view("adverts.show",compact("document","user"));
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
