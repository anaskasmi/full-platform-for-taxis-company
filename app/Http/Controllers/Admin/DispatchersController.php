<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Dispatcher;
use App\Archive;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\Dispatcher as DispatcherResource;

class DispatchersController extends Controller
{




    public function index()
    {
        $dispatchers = Dispatcher::orderBy('created_at','desc')->paginate(30);
        return DispatcherResource::collection($dispatchers);
    }








    public function search(Request $request)
    {
        $data = request()->validate([
            'searchValue' => 'required',
                    ]);
        
        $dispatchers = Dispatcher::where('name','LIKE','%'.$data['searchValue'].'%')
        ->paginate(30)
        ->appends(request()->query());
        return DispatcherResource::collection($dispatchers);

    }










    
    
    public function storeImage($dispatcher)
    {   

        if(request()->hasfile('image'))
        {
            $dispatcher->update([
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
            'email'=>'required|email|unique:dispatchers,email',
                    ]);
        
        $this->validateImages();

        if($request->input('password'))
        {
            request()->validate([
            'password' => 'required|string|min:6|confirmed',
            ]);
            $data['password'] = Hash::make($request->input('password'));
            // $data['password'] = $request->input('password');
        }
        else
        {
            $data['password'] = Hash::make('taxi-office');
            // $data['password'] = 'taxi-office';            
        }

        $dispatcher = Dispatcher::create($data);
        $this->storeImage($dispatcher);
        return new DispatcherResource($dispatcher);

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
            $data['password'] = Hash::make($request->input('password'));
            // $data['password'] = $re                                                                      quest->input('password');
        }
        try {
            Dispatcher::whereId($id)->update($data);
            $dispatcher=Dispatcher::whereId($id)->get();            
        }catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'error' => $e->errorInfo[2],
            ]);
        }

        $this->storeImage($dispatcher[0]);
        return new DispatcherResource($dispatcher);
    }



        
        
        





    public function show($id)
    {
        $dispatcher = Dispatcher::where('id', $id)->firstOrFail();
        return new DispatcherResource($dispatcher);
    }




    public function destroy($id)
    {
        $dispatcher = Dispatcher::findOrFail($id);
        if($dispatcher->delete())
        {
            return new DispatcherResource($dispatcher);
        }
    }
}
