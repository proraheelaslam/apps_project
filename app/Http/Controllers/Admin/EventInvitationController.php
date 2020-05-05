<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EventInvitationController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function invite(Request $request)
    {

        try {
            if (isset($request->event)) {
                $query_string = 'event';
                $id = $request->event;
            }
            $data['device'] = $this->userAgent();

            if ($this->userAgent() == 'android') {
                return redirect('nextneighbourApp://www.m.com/?' . $query_string . '=' . $id);
            } else if ($this->userAgent() == 'ios') {
                return redirect('nextneighbourApp://www.nextneighbourApp.com/' . $query_string . '=' . $id);
            } else {
                return redirect('/');
            }

        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * @return string
     */
    private function userAgent()
    {
        $iPod = strpos($_SERVER['HTTP_USER_AGENT'], "iPod");
        $iPhone = strpos($_SERVER['HTTP_USER_AGENT'], "iPhone");
        $iPad = strpos($_SERVER['HTTP_USER_AGENT'], "iPad");
        $android = strpos($_SERVER['HTTP_USER_AGENT'], "Android");
        if ($iPad || $iPhone || $iPod) {
            return 'ios';
        } else if ($android) {
            return 'android';
        } else {
            return 'pc';
        }
    }
}
