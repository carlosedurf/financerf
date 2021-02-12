<?php

namespace App\Http\Livewire\Plan;

use App\Services\PagSeguro\Plan\PlanCreateService;
use Livewire\Component;
use App\Models\Plan;

class PlanCreate extends Component
{

    public array $plan = [];

    public $rules = [
        "plan.name"         =>  "required",
        "plan.description"  =>  "required",
        "plan.price"        =>  "required",
        "plan.slug"         =>  "required"
    ];

    public function createPlan()
    {
        $this->validate();

        $plan = $this->plan;

        $plan['reference'] = (new PlanCreateService())->makeRequest($plan);

        $this->plan = [];

        Plan::create($plan);

        session()->flash('message', 'Plano Criado com Sucesso!');
    }

    public function render()
    {
        return view('livewire.plan.plan-create');
    }

}
