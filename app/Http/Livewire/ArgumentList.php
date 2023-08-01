<?php

namespace App\Http\Livewire;

use App\Models\Argument;
use App\Models\Rfc;
use App\Models\User;
use Livewire\Component;

class ArgumentList extends Component
{
    public Rfc $rfc;

    public ?User $user = null;

    protected $listeners = [
        Events::USER_VOTED->value => 'handleUserVoted',
        Events::ARGUMENT_CREATED->value => 'handleUserVoted',
    ];

    public function render()
    {
        return view('livewire.argument-list');
    }

    public function handleUserVoted(): void
    {
        $this->rfc->refresh();
    }

    public function handleArgumentCreated(): void
    {
        $this->rfc->refresh();
    }

    public function voteForArgument(Argument $argument): void
    {
        if (!$this->user) {
            return;
        }

        $this->user->toggleArgumentVote($argument);

        $this->user->refresh();
        $this->rfc->refresh();
        $this->emit(Events::REPUTATION_UPDATED);
    }
}
