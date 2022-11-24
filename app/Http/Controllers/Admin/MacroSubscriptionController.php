<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Flash;
use Response;
use App\Models\MacroSubscription;

class MacroSubscriptionController extends AppBaseController
{
    function destroy($id){
        $macro_subscription = MacroSubscription::find($id);
        if (empty($macro_subscription)) {
            Flash::error('Squadra non trovata');
            return response()->json( array('status' => 'error'));
        }
        
        $macro_subscription->delete();
        
        Flash::success('Squadra eliminata.');

        return response()->json( array('status' => 'ok'));
    }
}
