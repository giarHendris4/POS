<?php

namespace App\Livewire\Transactions;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class History extends Component
{
    use WithPagination;

    public $startDate;
    public $endDate;
    public $selectedDate;
    public $filterType = 'range';
    public $showTodayOnly = false;

    protected $queryString = [
        'startDate' => ['except' => ''],
        'endDate' => ['except' => ''],
        'selectedDate' => ['except' => ''],
        'filterType' => ['except' => 'range'],
    ];

    public function mount()
    {
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate = now()->format('Y-m-d');
    }

    public function updatedFilterType()
    {
        if ($this->filterType === 'today') {
            $this->selectedDate = now()->format('Y-m-d');
            $this->startDate = null;
            $this->endDate = null;
        } else {
            $this->selectedDate = null;
            $this->startDate = now()->startOfMonth()->format('Y-m-d');
            $this->endDate = now()->format('Y-m-d');
        }

        $this->resetPage();
    }

    public function updatedSelectedDate()
    {
        $this->resetPage();
    }

    public function updatedStartDate()
    {
        $this->resetPage();
    }

    public function updatedEndDate()
    {
        $this->resetPage();
    }

    public function getTransactionsProperty()
    {
        $user = Auth::user();
        $query = Transaction::with(['user', 'branch', 'items'])
            ->where('tenant_id', $user->tenant_id)
            ->where('status', 'completed');

        if ($user->isCashier() && $user->branch_id) {
            $query->where('branch_id', $user->branch_id);
        }

        if ($this->filterType === 'today' && $this->selectedDate) {
            $query->whereDate('transaction_date', Carbon::parse($this->selectedDate)->timezone('Asia/Jakarta')->toDateString());
        } elseif ($this->filterType === 'range' && $this->startDate && $this->endDate) {
            $query->whereBetween('transaction_date', [
                Carbon::parse($this->startDate)->timezone('Asia/Jakarta')->startOfDay(),
                Carbon::parse($this->endDate)->timezone('Asia/Jakarta')->endOfDay(),
            ]);
        }

        return $query->orderBy('transaction_date', 'desc')->paginate(20);
    }

    public function getTotalAmountProperty()
    {
        $user = Auth::user();
        $query = Transaction::where('tenant_id', $user->tenant_id)
            ->where('status', 'completed');

        if ($user->isCashier() && $user->branch_id) {
            $query->where('branch_id', $user->branch_id);
        }

        if ($this->filterType === 'today' && $this->selectedDate) {
            $query->whereDate('transaction_date', Carbon::parse($this->selectedDate)->timezone('Asia/Jakarta')->toDateString());
        } elseif ($this->filterType === 'range' && $this->startDate && $this->endDate) {
            $query->whereBetween('transaction_date', [
                Carbon::parse($this->startDate)->timezone('Asia/Jakarta')->startOfDay(),
                Carbon::parse($this->endDate)->timezone('Asia/Jakarta')->endOfDay(),
            ]);
        }

        return $query->sum('grand_total');
    }

    public function getTotalTransactionsProperty()
    {
        $user = Auth::user();
        $query = Transaction::where('tenant_id', $user->tenant_id)
            ->where('status', 'completed');

        if ($user->isCashier() && $user->branch_id) {
            $query->where('branch_id', $user->branch_id);
        }

        if ($this->filterType === 'today' && $this->selectedDate) {
            $query->whereDate('transaction_date', Carbon::parse($this->selectedDate)->timezone('Asia/Jakarta')->toDateString());
        } elseif ($this->filterType === 'range' && $this->startDate && $this->endDate) {
            $query->whereBetween('transaction_date', [
                Carbon::parse($this->startDate)->timezone('Asia/Jakarta')->startOfDay(),
                Carbon::parse($this->endDate)->timezone('Asia/Jakarta')->endOfDay(),
            ]);
        }

        return $query->count();
    }

    public function render()
    {
        return view('livewire.transactions.history', [
            'transactions' => $this->transactions,
            'totalAmount' => $this->totalAmount,
            'totalTransactions' => $this->totalTransactions,
        ])->layout('layouts.app');
    }
}
