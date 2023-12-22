<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;
use App\Models\Category;

class MailController extends Controller
{
    public function contact(){
        $categories = Category::whereNull('deleted_at')->get();

        return view('user.contact.show_contact', compact('categories'));
    }
    public function post_contact(Request $request){
        Mail::send('user.send_mail',[
            'name' => $request->name,
            'message'=>$request->message,
        ], function($mail) use ($request){
            $mail->to('lan@gmail.com', $request->name);
            $mail->from($request->email);
            $mail->subject('Test email');
        });
    }
}
