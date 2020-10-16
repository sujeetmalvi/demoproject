<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller as BaseController;
use Mail;


class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    public function sendEmail($subject, $login_url,$username,$password, $emailTo, $emailFrom = "")
    {
        try {
            //Log::info('Sending email ' . implode(",",$emailTo).' and subject '.$subject.' and msg '.$message);
            $from = empty($emailFrom) ? config('tableConstants.MAIL_USERNAME') : $emailFrom;
            //Log::info($from);
            $to = $emailTo;

            Mail::send('emails.email_text',
                [
                    'login_url'=> $login_url,
                    'username' => $username,
                    'password' => $password

                ], function ($mailSend) use ($from, $subject, $to) {
                    $mailSend->from($from, 'Superfence');
                    $mailSend->to($to, 'Superfence User')
                        ->subject($subject);
                });
        } catch (Exception $e) {
            Log::info('email line '.$e->getLine(). ' Error in sending email ' . $e->getMessage());
            //$returnArray['message'] = $e->getMessage();
        }
    }
}
