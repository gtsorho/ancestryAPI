<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ancestor;
use App\Models\memories;
use App\Models\relations;

class ancestryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ancestor = ancestor::with(['relations','memories'])->get();

        return response()->json($ancestor, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        $fields = $request->validate([
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'othernames' => 'required|string',
            'dob' => 'nullable|date',
            'dod' => 'required|date',
            'url' => 'required|array',
            'bioRelation' => 'required|array',
            'placeofBirth' => 'nullable|string',
            'finalResidence' => 'required|string',
            'hometown' => 'required|string',
            'FamilyName' => 'required|string',
            'territories' => 'required|array',
            'occupation' => 'required|array',
            'biography' => 'required|string',
            'causeofDeath' => 'required|string',
            'links'=> 'nullable|array',
        ]);

            $fields['occupation'] = json_encode($fields['occupation']);
            $fields['territories'] = json_encode($fields['territories']);
            $fields['links'] = json_encode($fields['links']);

            $ancestry = $user->ancestors()->create(collect($fields)->except(['url', 'bioRelation'])->toArray());

            foreach($fields['bioRelation'] as $relative => $relation) {
                $bioRelation[] = relations::create([
                    'ancestor_id'=> $ancestry['id'],
                    'relation' => $relation,
                    'relativeName' => $relative,
                ]);
            }

            foreach($fields['url'] as $key => $value) {
                if(!collect($value)->has('imageDescription')){
                    $value['imageDescription'] = '' ;
                }
                $url[] = memories::create([
                    'ancestor_id'=> $ancestry['id'],
                    'images' => '',
                    'imageDescription' => $value['imageDescription'],
                ]);
            }
        return [$ancestry, $bioRelation, $url, 'ancestor_id'=>$ancestry['id']] ;
    }

    public function filePath($filePath)
    {
        $fileParts = pathinfo($filePath);
        
        if(!isset($fileParts['filename']))
        {$fileParts['filename'] = substr($fileParts['basename'], 0, strrpos($fileParts['basename'], '.'));}
        
        return $fileParts;
    }

    public function storeimg(request $request){
        $list = memories::where('ancestor_id', $request->newUser)->get('id');
        $listimg = memories::where('ancestor_id', $request->newUser)->get('images');

        // return $request->images;
        // if($request->hasFile('images')){
            foreach($request->images as $key => $value){
                $productImage = $value->store('uploads', 'public'); //create image path
                $image = asset(\Storage::url($productImage));//create image url

                $localurl = $this->filePath($listimg[$key]->images);
                Storage::delete($localurl->basename);

                memories::where('ancestor_id', $request->newUser)->where('id', $list[$key]->id)->update(['images' => $image]);
            }
        // }
        return response()->json( memories::where('ancestor_id', $request->newUser)->get(), 200);
    }

    
    public function search($search = ""){
        $data = ancestor::where('FamilyName', 'like', '%'.$search.'%')
        ->orWhere('lastname', 'like', '%'.$search.'%')
        ->orWhere('othernames', 'like', '%'.$search.'%')
        ->orWhere('hometown', 'like', '%'.$search.'%')
        ->with(['relations','memories'])->latest()->get();
        return response()->json($data, 200);
    }

    public function adminsearch($searchval = ""){
        $user =  auth()->user();
        $data = ancestor::where([['FamilyName', 'like', '%'.$searchval.'%'],['user_id',$user->id]])
        ->orWhere([['lastname', 'like', '%'.$searchval.'%'],['user_id',$user->id]])
        ->orWhere([['othernames', 'like', '%'.$searchval.'%'], ['user_id',$user->id]])
        ->orWhere([['hometown', 'like', '%'.$searchval.'%'], ['user_id',$user->id]])
        ->with(['relations','memories'])->latest()->get();
        return response()->json($data, 200);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $ancestor = ancestor::where('id',$id)->first();
        // $ancestor = ancestor::find(1);
        $ancestor->relations;
        $ancestor->memories;

        return response()->json([ $ancestor], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {   
        $user = auth()->user();
        $fields = $request->validate([
            'firstname' => 'nullable|string',
            'lastname' => 'nullable|string',
            'othernames' => 'nullable|string',
            'dob' => 'nullable|date',
            'dod' => 'nullable|date',
            'url' => 'nullable|array',
            'bioRelation' => 'nullable|array',
            'placeofBirth' => 'nullable|string',
            'finalResidence' => 'nullable|string',
            'hometown' => 'nullable|string',
            'FamilyName' => 'nullable|string',
            'territories' => 'nullable|array',
            'occupation' => 'nullable|array',
            'biography' => 'nullable|string',
            'causeofDeath' => 'nullable|string',
            // 'images' => 'file|mimes:jpeg,jpg,png,bmp,tiff',
            'links'=> 'nullable|array',
        ]);

            $fields['occupation'] = json_encode($fields['occupation']);
            $fields['territories'] = json_encode($fields['territories']);
            $fields['links'] = json_encode($fields['links']);

            $ancestry = $user->ancestors()->where('id',$id)->update(collect($fields)->except(['url', 'bioRelation'])->toArray());

            foreach($fields['bioRelation'] as $relative => $relation) {
                $bioRelation[] = relations::where('ancestor_id',$id)->update([
                    'relation' => $relation,
                    'relativeName' => $relative,
                ]);
            }
            foreach($fields['url'] as  $value) {
                    if(!collect($value)->has('imageDescription')){
                        $value['imageDescription'] = '' ;
                    }
                    $url[] = memories::where('ancestor_id',$id)->update([
                        'imageDescription' => $value['imageDescription'],
                    ]);
            }
        return response()->json([$this->show($id),'ancestor_id'=>$id], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {   
        $user = auth()->user();
        $ancestor = $user->ancestors()->where('id',$id)->delete();
        return response()->json($ancestor, 200);
    }
}
