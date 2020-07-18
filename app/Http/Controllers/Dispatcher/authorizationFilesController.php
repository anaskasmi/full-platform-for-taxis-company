<?php

namespace App\Http\Controllers\Dispatcher;

use App\AuthorizationFile;
use App\Dispatcher;
use App\Http\Controllers\Controller;
use App\Http\Resources\AuthorizationFile as AuthorizationFileResource;
use Auth;
use Illuminate\Http\Request;

class authorizationFilesController extends Controller
{

    //search authorizationFile
    public function search(Request $request)
    {

        if ($request->input('searchByAccountNumber')) {
            if (!is_numeric($request->input('searchByAccountNumber'))) {
                return response()->json([
                    'error' => "Account Number must be numeric",
                ]);
            }
            $data = request()->validate([
                'searchByAccountNumber' => '',
            ]);
            $authorizationFile = AuthorizationFile::
                select(
                'id',
                'file_path',
                'owner_id',
                'first_name',
                'last_name',
                'account_number',
                'account_type',
                'po_number',
                'expiry_date',
                'status',
                'created_at',
                'updated_at'
            )
                ->where('account_number', 'LIKE', '%'.$data['searchByAccountNumber']. '%')
                ->orderBy('updated_at', 'desc')
                ->paginate(20)
                ->appends(request()->query());
            return AuthorizationFileResource::collection($authorizationFile);
        }
        //Search by ExpiryDate
        if ($request->input('searchByExpiryDate')) {
            $data = request()->validate([
                'searchByExpiryDate' => 'date',
            ]);
            $date = strtotime($data['searchByExpiryDate']);
            $date = date('Y-m-d', $date);

            $authorizationFile = AuthorizationFile::
                select(
                'id',
                'file_path',
                'owner_id',
                'first_name',
                'last_name',
                'account_number',
                'account_type',
                'po_number',
                'expiry_date',
                'status',
                'created_at',
                'updated_at'
            )
                ->where('expiry_date', 'LIKE', '%' . $date . '%')
                ->orderBy('updated_at', 'desc')
                ->paginate(20)
                ->appends(request()->query());
            return AuthorizationFileResource::collection($authorizationFile);

        }
        //Search by Account Name
        if ($request->input('searchByAccountName')) {
            $data = request()->validate([
                'searchByAccountName' => 'required',
            ]);

            $authorizationFile = AuthorizationFile::
                select(
                'id',
                'file_path',
                'owner_id',
                'first_name',
                'last_name',
                'account_number',
                'account_type',
                'po_number',
                'expiry_date',
                'status',
                'created_at',
                'updated_at'
            )
                ->where('last_name', 'LIKE', '%' . $data['searchByAccountName'] . '%')
                ->orwhere('first_name', 'LIKE', '%' . $data['searchByAccountName'] . '%')->orderBy('updated_at', 'desc')
                ->paginate(20)
                ->appends(request()->query());
            return AuthorizationFileResource::collection($authorizationFile);

        }

    }

    //list all the AuthorizationFiles
    public function authorizationFiles()
    {
        $authorizationFile = AuthorizationFile::
            select(
            'id',
            'file_path',
            'owner_id',
            'first_name',
            'last_name',
            'account_number',
            'account_type',
            'po_number',
            'expiry_date',
            'status',
            'created_at',
            'updated_at'
        )
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->appends(request()->query());
        return AuthorizationFileResource::collection($authorizationFile);
    }

    public function myauthorizationFile()
    {
        $myauthorizationFile = AuthorizationFile::
            select(
            'file_path',
            'owner_id',
            'first_name',
            'last_name',
            'account_number',
            'account_type',
            'po_number',
            'expiry_date',
            'status',
            'created_at',
            'updated_at'
        )
            ->where('authorizationFile.owner_id', "=", Auth::user()->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->appends(request()->query());
        return AuthorizationFileResource::collection($myauthorizationFile);
    }

    //show a single comment
    public function show($id)
    {
        $authorizationFile = AuthorizationFile::
            select(
            'id',
            'file_path',
            'owner_id',
            'first_name',
            'last_name',
            'account_number',
            'account_type',
            'po_number',
            'expiry_date',
            'status',
            'created_at',
            'updated_at',
            'comment',
            'updater_id'
        )
            ->where('id', '=', $id)
            ->firstOrFail();
        $owner = Dispatcher::where('id', $authorizationFile->owner_id)->firstOrFail();
        $authorizationFile->owner = $owner->name;
        if ($authorizationFile->updater_id) {
            $updater = Dispatcher::where('id', $authorizationFile->updater_id)->firstOrFail();
            $authorizationFile->updater = $updater->name;
        }
        return new AuthorizationFileResource($authorizationFile);
    }

    //update an existing comment
    public function update(Request $request, $id)
    {
        $this->validateImages();

        $data = request()->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'account_number' => 'required ',
            'account_type' => 'required',
            'po_number' => 'required',
            'expiry_date' => 'required',
            'comment' => '',
            'status' => 'required',
        ]);
        $data['updater_id'] = Auth::user()->id;
        try {
            AuthorizationFile::
                whereId($id)
                ->update($data);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => "error in updating the columns"], 401);
        }
        $authorizationFile = AuthorizationFile::whereId($id)->first();
        if (request()->hasfile('file_path')) {
            if ($authorizationFile->file_path) {
                unlink(storage_path('app/public/' . $authorizationFile->file_path));
            }

        }
        $this->storeImage($authorizationFile);

        $authorizationFile = AuthorizationFile::whereId($id)->get();
        return new AuthorizationFileResource($authorizationFile);

    }

    //store
    public function store()
    {

        $data = request()->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'account_number' => 'required',
            'account_type' => 'required',
            'po_number' => 'required',
            'expiry_date' => 'required',
            'status' => 'required',
            'file_path' => 'required|mimes:jpeg,bmp,png,jpg,gif,png,pdf'
        ]);
        $data['owner_id'] = Auth::user()->id;

        $authorizationFile = AuthorizationFile::create($data);
        $this->storeImage($authorizationFile);
        return new AuthorizationFileResource($authorizationFile);
    }

    //delete an existing authorization File

    public function destroy($id)
    {
        $authorizationFile = AuthorizationFile::findOrFail($id);
        if ($authorizationFile->file_path) {
            unlink(storage_path('app/public/' . $authorizationFile->file_path));
        }

        if ($authorizationFile->delete()) {
            return new AuthorizationFileResource($authorizationFile);
        }
    }
    public function validateImages()
    {
        if (request()->hasfile('file_path')) {
            request()->validate(
                [
                    'file_path' => 'required|mimes:jpeg,bmp,png,jpg,gif,png,pdf'
                ]
            );
        }
    }

    public function storeImage($authorizationFile)
    {
        if (request()->hasfile('file_path')) {
            $authorizationFile->update([
                'file_path' => request()->file_path->store('uploads/authorizationFiles', 'public'),
            ]);
        }
    }
}
