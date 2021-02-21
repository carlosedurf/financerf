<?php

namespace App\Http\Middleware\Subscription;

use App\Traits\Subscription\SubscriptionTrait;
use Closure;
use Illuminate\Http\Request;

class CheckAmountOfExpenseUserPlan
{

    use SubscriptionTrait;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        if($this->userCanNotANewExpense()){
            session()->flash('message', 'Você já chegou no limite de registro do seu plano! Deseja fazer o upgrade?');

            return redirect()->route('expenses.index');
        }


        return $next($request);
    }
}
