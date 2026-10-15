@extends('layouts.app')
@section('title', 'Matches - Admin')
@push('head') @vite('resources/css/admin.css') @endpush

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Match Management</h1>
        <p class="text-gray-600">Manage tutor-parent connections, commissions, and contact sharing</p>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            {{ session('error') }}
        </div>
    @endif

    <!-- Status Tabs -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="border-b border-gray-200">
            <nav class="flex -mb-px overflow-x-auto">
                <a href="{{ route('admin.matches.index', ['status' => 'all']) }}"
                   class="px-6 py-4 text-sm font-medium border-b-2 whitespace-nowrap {{ $status === 'all' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    All Matches
                    <span class="ml-2 px-2 py-1 text-xs rounded-full {{ $status === 'all' ? 'bg-blue-100 text-blue-600' : 'bg-gray-100 text-gray-600' }}">
                        {{ $counts['all'] }}
                    </span>
                </a>
                <a href="{{ route('admin.matches.index', ['status' => 'pending_admin']) }}"
                   class="px-6 py-4 text-sm font-medium border-b-2 whitespace-nowrap {{ $status === 'pending_admin' ? 'border-yellow-500 text-yellow-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Pending Action
                    <span class="ml-2 px-2 py-1 text-xs rounded-full {{ $status === 'pending_admin' ? 'bg-yellow-100 text-yellow-600' : 'bg-gray-100 text-gray-600' }}">
                        {{ $counts['pending_admin'] }}
                    </span>
                </a>
                <a href="{{ route('admin.matches.index', ['status' => 'contacted']) }}"
                   class="px-6 py-4 text-sm font-medium border-b-2 whitespace-nowrap {{ $status === 'contacted' ? 'border-teal-500 text-teal-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Contacted
                    <span class="ml-2 px-2 py-1 text-xs rounded-full {{ $status === 'contacted' ? 'bg-teal-100 text-teal-600' : 'bg-gray-100 text-gray-600' }}">
                        {{ $counts['contacted'] }}
                    </span>
                </a>
                <a href="{{ route('admin.matches.index', ['status' => 'negotiating']) }}"
                   class="px-6 py-4 text-sm font-medium border-b-2 whitespace-nowrap {{ $status === 'negotiating' ? 'border-amber-500 text-amber-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Negotiating
                    <span class="ml-2 px-2 py-1 text-xs rounded-full {{ $status === 'negotiating' ? 'bg-amber-100 text-amber-600' : 'bg-gray-100 text-gray-600' }}">
                        {{ $counts['negotiating'] }}
                    </span>
                </a>
                <a href="{{ route('admin.matches.index', ['status' => 'commission_paid']) }}"
                   class="px-6 py-4 text-sm font-medium border-b-2 whitespace-nowrap {{ $status === 'commission_paid' ? 'border-green-500 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Commission Paid
                    <span class="ml-2 px-2 py-1 text-xs rounded-full {{ $status === 'commission_paid' ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-600' }}">
                        {{ $counts['commission_paid'] }}
                    </span>
                </a>
                <a href="{{ route('admin.matches.index', ['status' => 'completed']) }}"
                   class="px-6 py-4 text-sm font-medium border-b-2 whitespace-nowrap {{ $status === 'completed' ? 'border-gray-500 text-gray-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Completed
                    <span class="ml-2 px-2 py-1 text-xs rounded-full {{ $status === 'completed' ? 'bg-gray-200 text-gray-700' : 'bg-gray-100 text-gray-600' }}">
                        {{ $counts['completed'] }}
                    </span>
                </a>
            </nav>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <form method="GET" action="{{ route('admin.matches.index') }}" class="flex gap-4">
            <input type="hidden" name="status" value="{{ $status }}">
            <div class="flex-1">
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Search by tutor or parent name, offer title..."
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                Search
            </button>
            @if(request('search'))
                <a href="{{ route('admin.matches.index', ['status' => $status]) }}"
                   class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                    Clear
                </a>
            @endif
        </form>
    </div>

    <!-- Matches List -->
    <div class="space-y-4">
        @forelse($matches as $match)
            <div class="bg-white rounded-lg shadow hover:shadow-md transition">
                <div class="p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="px-2 py-1 text-xs font-medium rounded {{ $match['type'] === 'application' ? 'bg-blue-100 text-blue-700' : 'bg-teal-100 text-teal-700' }}">
                                    {{ $match['type'] === 'application' ? 'Application' : 'Invitation' }}
                                </span>
                                <span class="px-2 py-1 text-xs font-medium rounded
                                    @if($match['status'] === 'with_admin') bg-yellow-100 text-yellow-700
                                    @elseif($match['status'] === 'contacted') bg-teal-100 text-teal-700
                                    @elseif($match['status'] === 'negotiating') bg-amber-100 text-amber-700
                                    @elseif($match['status'] === 'commission_paid') bg-green-100 text-green-700
                                    @elseif($match['status'] === 'completed') bg-gray-100 text-gray-700
                                    @endif">
                                    {{ ucfirst(str_replace('_', ' ', $match['status'])) }}
                                </span>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-1">
                                {{ $match['offer']->title ?? 'General Tutoring' }}
                            </h3>
                            <div class="text-sm text-gray-600 space-y-1">
                                <p><strong>Tutor:</strong> {{ $match['tutor']->name }} ({{ $match['tutor']->email }})</p>
                                <p><strong>Parent:</strong> {{ $match['parent']->name ?? 'N/A' }} ({{ $match['parent']->email ?? 'N/A' }})</p>
                                <p><strong>Sent to Admin:</strong> {{ optional($match['sent_to_admin_at'])->format('M d, Y h:i A') ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="ml-4">
                            <a href="{{ route('admin.matches.show', ['type' => $match['type'], 'id' => $match['id']]) }}"
                               class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition inline-block">
                                View Details
                            </a>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="flex gap-2 pt-4 border-t border-gray-200">
                        @if($match['status'] === 'with_admin')
                            <form method="POST" action="{{ route('admin.matches.mark-contacted', ['type' => $match['type'], 'id' => $match['id']]) }}" class="inline">
                                @csrf
                                <button type="submit" class="px-3 py-1 text-sm bg-teal-100 text-teal-700 rounded hover:bg-teal-200 transition">
                                    Mark as Contacted
                                </button>
                            </form>
                        @elseif($match['status'] === 'contacted' && $match['type'] === 'invitation')
                            <form method="POST" action="{{ route('admin.matches.mark-negotiating', ['type' => $match['type'], 'id' => $match['id']]) }}" class="inline">
                                @csrf
                                <button type="submit" class="px-3 py-1 text-sm bg-amber-100 text-amber-700 rounded hover:bg-amber-200 transition">
                                    Mark as Negotiating
                                </button>
                            </form>
                        @elseif(in_array($match['status'], ['contacted', 'negotiating']) && !isset($match['commission_id']))
                            <span class="text-sm text-gray-600">⚠ Commission not yet recorded</span>
                        @elseif($match['status'] === 'commission_paid')
                            <span class="text-sm text-green-600">✓ Ready to share contact details</span>
                        @elseif($match['status'] === 'completed')
                            <span class="text-sm text-gray-600">✓ Completed - Contact shared</span>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-lg shadow p-8 text-center">
                <div class="text-gray-400 text-5xl mb-4">📋</div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No matches found</h3>
                <p class="text-gray-600">
                    @if($status !== 'all')
                        No matches with status "{{ ucfirst(str_replace('_', ' ', $status)) }}"
                    @else
                        No matches available yet. Matches will appear here when parents accept applications or tutors accept invitations.
                    @endif
                </p>
            </div>
        @endforelse
    </div>
</div>
@endsection
