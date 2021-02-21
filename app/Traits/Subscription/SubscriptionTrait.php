<?php


namespace App\Traits\Subscription;


trait SubscriptionTrait
{

    public function loadFeatureByUserPlan($type = null)
    {

        $userPlan = auth()->user()->plan();

        if(!$userPlan->exists())
            return [];

        return $userPlan->first()->plan->features()->whereType($type)->get();
    }

    public function userCanNotANewExpense()
    {

        $userPlanFeatures = $this->loadFeatureByUserPlan('amount');

        if(!$userPlanFeatures)
            return false;

        $amountFeature = $this->loadFeatureByUserPlan('amount')->first()->rule['amount'];
        $userExpenseAmount = auth()->user()->expenses->count();

        return $userExpenseAmount >= $amountFeature;
    }

}
