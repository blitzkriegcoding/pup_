<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Requests\CreateCreditRequest;

class PlanQuoteController extends Controller
{
    //

    public function newCredit()
    {
    	return view('new_credit');
    }

    public function createCredit(CreateCreditRequest $request)
    {

    }
}
