<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Director;
use App\Archive;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\Director as DirectorResource;

class DirectorsController extends Controller
{


    public function index()
    {
        $directors = Director::orderBy('created_at','desc')->paginate(30);
        return DirectorResource::collection($directors);
    }


    public function search(Request $request)
    {
        $data = request()->validate([
            'searchValue' => 'required',
                    ]);
        
        $directors = Director::where('name','LIKE','%'.$data['searchValue'].'%')
        ->paginate(30)
        ->appends(request()->query());
        return DirectorResource::collection($directors);
    }





    
    
    public function storeImage($director)
    {   

        if(request()->hasfile('image'))
        {
            $director->update([
                'image'=>request()->image->store('uploads/IMAGES','public'),
                ]);
            } 
  
    

    }






    public function validateImages()
    {
        if(request()->hasfile('image'))
        {
            request()->validate(
                [
                    'image'=>'file|image|max:30000',
                ]
            );
        }

    }




    


    public function store(Request $request)
    {

        $data = request()->validate([
            'name' => 'required',
            'email'=>'required|email|unique:directors,email',
                    ]);
        
        $this->validateImages();

        if($request->input('password'))
        {
            request()->validate([
            'password' => 'required|string|min:6|confirmed',
            ]);
            // $data['password'] = Hash::make($request->input('password'));
            $data['password'] = $request->input('password');
        }
        else
        {
            // $data['password'] = Hash::make('taxi-office');
            $data['password'] = 'taxi-office';            
        }
        
        $director = Director::create($data);
        $this->storeImage($director);
        return new DirectorResource($director);

    }



    public function update(Request $request, $id)
    {
        
        $data = request()->validate([
            'name' => 'required',
            'email'=>'required|email',

                    ]);
        if($request->input('password'))
        {
            request()->validate([
            'password' => 'required|string|min:6|confirmed',
            ]);
            // $data['password'] = Hash::make($request->input('password'));
            $data['password'] = $request->input('password');
        }
        $this->validateImages();
        try {

            Director::whereId($id)->update($data);
            $director=Director::whereId($id)->first();            
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'error' => $e->errorInfo[2],
            ]);
        }

        $this->storeImage($director);
        return new DirectorResource($director);
    }



        
        
        





    public function show($id)
    {
        $director = Director::where('id', $id)->firstOrFail();
        return new DirectorResource($director);
    }





    public function destroy($id)
    {
        $director = Director::findOrFail($id);
        if($director->delete())
        {
            return new DirectorResource($director);
        }
    }
}
