<?php

namespace App\Http\Controllers\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Driver;
use App\Http\Controllers\Controller;
use App\Http\Resources\VoiceMessage as VoiceMessageResource;
use App\VoiceMessage;
//to do
//upload the packages
class letsTalkController extends Controller
{
    //download Voice Message by ID
    public function downloadVoiceMessage($voiceMessageId)
    {
        $voiceMessage = VoiceMessage::whereId($voiceMessageId)->first();
        if ($voiceMessage) {
            $path = public_path($voiceMessage->file_path);
            // return public_path();
            // $path = "/home/nv9wqeaq9ypl/chilliwack-taxi/public_html/" . $voiceMessage->file_path;
            return response()->download($path);
        } else {
            return response()->json([
                'error' => 'Sorry, Audio is no longer Available !'], 400);

        }
    }
    //return all voice messages templates
    public function getVoiceMessagesTemplates()
    {
        $voiceMessages = VoiceMessage::
            get();
        return VoiceMessageResource::collection($voiceMessages);
    }

    //return new messages that have id more than the id passed in tha params
    public function getNewVoiceMessages($lastVoiceMessageId)
    {
        $voiceMessages = VoiceMessage::
            where('id', ">", $lastVoiceMessageId)
            ->get();
        return VoiceMessageResource::collection($voiceMessages);
    }
    //upload voice message
    public function storeVoiceMessage(Request $request)
    {
        // validation
        $data = $request->validate([
            'voice_message' => 'required|file|mimes:audio/mpeg,mpga,mp3,wav,aac',
            'duration' => 'required',
        ]);
        //constructing file name
        $uniqueid = uniqid();
        $size = $request->file('voice_message')->getSize();
        // $audio = new \wapmorgan\Mp3Info\Mp3Info($request->file('voice_message'));
        // $duration =floor($audio->duration) ;
        $extension = $request->file('voice_message')->getClientOriginalExtension();
        $filename =date("dMY")."_".date("h_i")."__".Auth::user()->PermitNumber."_". $uniqueid . '.' . $extension;
        // storing file
        // $fullVoiceMessagePath = url('storage/uploads/voice_messages/' . $filename);
        $MessageVoicePath='storage/uploads/voice_messages/'.$filename;
        $path = $request->file('voice_message')->storeAs('public/uploads/voice_messages/', $filename);
        //storing voice message info in the database
        
        $voiceMessageRow = new VoiceMessage();
        $voiceMessageRow->duration_in_sec= $request->Input('duration');
        $voiceMessageRow->owner_id= Auth::user()->PermitNumber;
        $voiceMessageRow->owner_full_name= Auth::user()->FirstName." ".Auth::user()->LastName;
        if(Auth::user()->image==""||Auth::user()->image==null)
        {
            $voiceMessageRow->picture_url= "https://mytaxioffice.com/storage/uploads/IMAGES/man.png";
        }
        else{
            $voiceMessageRow->picture_url= "https://mytaxioffice.com/storage/".Auth::user()->image;
        }
        $voiceMessageRow->file_path= $MessageVoicePath;
        $voiceMessageRow->save();

        return response()->json([
            'message' => 'uploaded successfully! '], 200);
    }

    public function deleteExpiredVoiceMessages()
    {
        //delete from database
        //delete from storage
    }

}
