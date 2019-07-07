<?php

namespace Modules\Setting\Http\Controllers;

use Artisan;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;

class ServiceController extends Controller
{
    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function clearCache()
    {
        Artisan::call('cache:clear');

        return redirect()->back();
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function clearView()
    {
        Artisan::call('view:clear');

        return redirect()->back();
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function emailCheck()
    {
        $user = auth()->user();
        $errors = [];

        try {
            Mail::send('setting::email.check', ['user' => $user], function ($mail) use ($user) {
                $mail->from(config('mail.from.address'), config('app.name'));

                $mail->to(config('mail.from.address'), $user)
                    ->subject('Проверка отправки почтовых сообщений')
                ;
            });
        } catch (\Swift_TransportException $e) {
            $errors[] = $e->getMessage();
        }

        return redirect()->back()->withErrors($errors);
    }
}
