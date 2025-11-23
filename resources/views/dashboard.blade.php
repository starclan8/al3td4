<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if($user->isFamilyAccount())
                <div class="mb-6 p-4 bg-blue-50 rounded">
                    <strong>Family Account:</strong>
                    <br>
                    Privacy: <span class="font-semibold">{{ ucfirst($user->privacy_level) }}</span>
                    <br>
                    Bio: {{ $user->bio ?? 'No bio provided.' }}
                </div>
            @endif

            @if($user->canSeekHelp())
                <div class="mb-8">
                    <h3 class="text-lg font-bold mb-2">Your Needs</h3>
                    @if($myNeeds->isEmpty())
                        <p>You have not posted any needs yet.</p>
                    @else
                        <ul class="list-disc pl-6">
                            @foreach($myNeeds as $need)
                                <li>
                                    <strong>{{ $need->title }}</strong>
                                    ({{ ucfirst($need->status) }}) - Needed by {{ $need->needed_by?->format('M d, Y') ?? 'N/A' }}
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            @endif

            @if($user->canProvideHelp())
                <div>
                    <h3 class="text-lg font-bold mb-2">Opportunities to Help</h3>
                    @if($helpOpportunities->isEmpty())
                        <p>No current needs available for you to help with.</p>
                    @else
                        <ul class="list-disc pl-6">
                            @foreach($helpOpportunities as $need)
                                <li>
                                    <strong>{{ $need->title }}</strong>
                                    ({{ $need->user->getDisplayNameAttribute() }}) - Needed by {{ $need->needed_by?->format('M d, Y') ?? 'N/A' }}
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
