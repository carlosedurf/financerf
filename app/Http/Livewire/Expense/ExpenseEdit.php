<?php

namespace App\Http\Livewire\Expense;

use App\Models\Expense;
use App\Traits\Subscription\SubscriptionTrait;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class ExpenseEdit extends Component
{

    use WithFileUploads, SubscriptionTrait;

    public Expense $expense;
    public $categories = [];

    public $description;
    public $amount;
    public $type;
    public $photo;

    protected $rules = [
        "description"   => "required",
        "amount"        => "required",
        "type"          => "required",
        "photo"         => "image|nullable"
    ];

    public function mount()
    {
        $this->description  = $this->expense->description;
        $this->amount       = $this->expense->amount;
        $this->type         = $this->expense->type;
        $this->categories   = $this->expense->categoriesArr;
    }

    public function updateExpense()
    {

        $this->validate();

        if($this->photo){
            if(Storage::disk('public')->exists($this->expense->photo)){
                Storage::disk('public')->delete($this->expense->photo);
            }

            $this->photo = $this->photo->store('expenses-photos', 'public');
        }


        $this->expense->update([
            "amount"        => $this->amount,
            "type"          => $this->type,
            "description"   => $this->description,
            "photo"         => $this->photo ?? $this->expense->photo
        ]);

        if(count($this->categories))
            $this->expense->categories()->sync($this->categories);

        session()->flash("message", "Registro atualizado com sucesso!");
    }

    public function render()
    {
        return view('livewire.expense.expense-edit')
                ->with('viewFeatures', $this->loadFeatureByUserPlan('view'));
    }

}
