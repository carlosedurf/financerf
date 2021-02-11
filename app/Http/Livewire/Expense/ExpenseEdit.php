<?php

namespace App\Http\Livewire\Expense;

use App\Models\Expense;
use Livewire\Component;

class ExpenseEdit extends Component
{

    public Expense $expense;

    public $description;
    public $amount;
    public $type;

    protected $rules = [
        "description" => "required",
        "amount" => "required",
        "type" => "required"
    ];

    public function mount()
    {
        $this->description  = $this->expense->description;
        $this->amount       = $this->expense->amount;
        $this->type         = $this->expense->type;
    }

    public function updateExpense()
    {

        $this->validate();

        $this->expense->update([
            "amount"        => $this->amount,
            "type"          => $this->type,
            "description"   => $this->description
        ]);

        session()->flash("message", "Registro atualizado com sucesso!");
    }

    public function render($expense)
    {
        return view('livewire.expense.expense-edit');
    }

}
