<?php

namespace App\Http\Controllers\Dispatcher;

use App\AccountType;
use App\Http\Controllers\Controller;
use App\Http\Resources\AccountType as AccountTypeResource;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class accountTypesController extends Controller
{
    //list all the accountTypes
    public function accountTypes()
    {
        $accountType = AccountType::
            select(
            'id',
            'name'
        )
            ->orderBy('created_at', 'desc')
            ->get();
        return AccountTypeResource::collection($accountType);
    }

    //get an AccountType by id
    public function show($id)
    {
        $accountType = AccountType::
            select(
            'id',
            'name'
        )
            ->where('id', '=', $id)
            ->firstOrFail();
        return new AccountTypeResource($accountType);
    }

    //update an existing accountType
    public function update(Request $request, $id)
    {

        $data = request()->validate([
            'name' => 'required',
        ]);

        try {
            AccountType::
                whereId($id)
                ->update($data);
            $accountType = AccountType::whereId($id)->get();
        } catch (\Illuminate\Database\QueryException $e) {
            return $e;
        }
        return new AccountTypeResource($accountType);

    }

    //store
    public function store()
    {
        $data = request()->validate([
            'name' => 'required',
        ]);
       
        $accountType = AccountType::create($data);
        return new AccountTypeResource($accountType);
    }

    //delete an existing accountType
    public function destroy($id)
    {
        $accountType = AccountType::findOrFail($id);
        if ($accountType->delete()) {
            return new AccountTypeResource($accountType);
        }
    }

}
