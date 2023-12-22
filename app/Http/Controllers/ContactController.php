<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Contact;

class ContactController extends Controller
{
    //
    public function add(Request $request)
    {
        // dd($request->all());
        $rule = [
            'name' => 'required',
            'email' => 'required|email',
            'content' => 'required',
        ];
        $messages = [
            'required' => 'Vui lòng nhập :attribute',
            'email' => 'Email không hợp lệ',
        ];
        $customName = [
            'name' => 'họ và tên',
            'email' => 'email',
            'content' => 'nội dung',
        ];
        $validator = Validator::make($request->all(),$rule,$messages,$customName);
        if($validator->fails())
        {
            return response()->json([
               'error'=>true,
               'message'=>$validator->errors(),
            ]);
        }
        $contact = new Contact();
        $contact->name = $request->name;
        $contact->email = $request->email;
        $contact->message = $request->content;
        if($contact->save())
        {
            return response()->json([
                'error'=>false,
                'message'=>null,
            ]);
        }
    }
}
