<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Archive;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\Archive as ArchiveResource;

class ArchivesController extends Controller
{




    public function index()
    {
        $archives= Archive::join('drivers','PermitNumber','=','badge_id')
        ->select(
            'archives.archive_id','drivers.FirstName','drivers.LastName',
            'archives.created_at','archives.LICENSE_PATH','archives.PERMIT_PATH',
            'archives.TAXIHOST_PATH','archives.ABSTRACT_PATH','archives.SP_FILE_PATH'
            )
        ->orderByRaw('archives.created_at DESC')
        ->paginate(20)
        ->appends(request()->query());
        return ArchiveResource::collection($archives);
    }



    


    public function search(Request $request)
    {

        //Search by Driver
        if($request->input('searchbydriver'))
        {
            $data = request()->validate([
                'searchbydriver' => 'required',
                        ]);
            $archives=Archive::
            join('drivers','PermitNumber','=','badge_id')
            ->select(
                'archives.archive_id','archives.badge_id','drivers.FirstName','drivers.LastName',
                'archives.created_at','archives.LICENSE_PATH','archives.PERMIT_PATH',
                'archives.TAXIHOST_PATH','archives.ABSTRACT_PATH','archives.SP_FILE_PATH'
                )
            ->where('LastName','LIKE','%'.$data['searchbydriver'].'%')
            ->orwhere('FirstName','LIKE','%'.$data['searchbydriver'].'%')
            ->orwhere('PermitNumber','LIKE','%'.$data['searchbydriver'].'%')
            ->orderByRaw('archives.created_at DESC')
            ->paginate(20)
            ->appends(request()->query());
            return ArchiveResource::collection($archives);
        }
    }



    public function show($id)
    {
        $archive=Archive::join('drivers','PermitNumber','=','badge_id')
        ->select(
            'archives.archive_id','archives.badge_id','drivers.FirstName','drivers.LastName',
            'archives.created_at','archives.LICENSE_PATH','archives.PERMIT_PATH',
            'archives.TAXIHOST_PATH','archives.ABSTRACT_PATH','archives.SP_FILE_PATH'
            )
        ->where('archives.archive_id','=',$id)
        ->first();
        return new ArchiveResource($archive);

    }

    public function destroy($id)
    {

            $archive =  Archive::where('archive_id', $id)->first();

            $storagePath  = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix().'public';
            if($archive->PERMIT_PATH)
            {
                unlink(storage_path('app/public/'.$archive->PERMIT_PATH));
            }
            if($archive->LICENSE_PATH)
            {
                unlink(storage_path('app/public/'.$archive->LICENSE_PATH));
            }
            if($archive->TAXIHOST_PATH)
            {
                unlink(storage_path('app/public/'.$archive->TAXIHOST_PATH));
            }
            if($archive->ABSTRACT_PATH)
            {
                unlink(storage_path('app/public/'.$archive->ABSTRACT_PATH));
            }
            if($archive->SP_FILE_PATH)
            {
                unlink(storage_path('app/public/'.$archive->SP_FILE_PATH));
            }



        if($archive->delete())
        {
            return new ArchiveResource($archive);
        }
    }

}
