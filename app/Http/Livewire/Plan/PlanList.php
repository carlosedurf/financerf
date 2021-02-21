<?php

namespace App\Http\Livewire\Plan;

use App\Models\Plan;
use Livewire\Component;

class PlanList extends Component
{

    public $showModal = false;

    protected $listeners = [
        'closeModal' => 'closeModal'
    ];

    public function openModal($planId)
    {
        $this->emit('openModal', $planId);
        $this->showModal = true;
    }

    public function closeModal($message)
    {
        $this->showModal = false;

        if($message)
            session()->flash('message', 'Feature criada com sucesso!');
    }

    public function render()
    {
        $plans = Plan::all(['id', 'name', 'price', 'created_at']);

        return view('livewire.plan.plan-list', compact('plans'));
    }
}
