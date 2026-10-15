@extends('layouts.app')
@section('title', 'Match - Admin')
@push('head') @vite('resources/css/admin.css') @endpush

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('admin.matches.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Matches
        </a>
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Match Info -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center gap-2 mb-4">
                    <span class="px-3 py-1 text-sm font-medium rounded {{ $type === 'application' ? 'bg-blue-100 text-blue-700' : 'bg-teal-100 text-teal-700' }}">
                        {{ $type === 'application' ? 'Application' : 'Invitation' }}
                    </span>
                    @php
                        $status = $type === 'application' ?
                            ($match->parent_contact_shared_at ? 'completed' : ($match->commission_id ? 'commission_paid' : ($match->admin_contacted_at ? 'contacted' : 'with_admin'))) :
                            $match->status;
                    @endphp
                    <span class="px-3 py-1 text-sm font-medium rounded
                        @if($status === 'with_admin') bg-yellow-100 text-yellow-700
                        @elseif($status === 'contacted') bg-teal-100 text-teal-700
                        @elseif($status === 'negotiating') bg-amber-100 text-amber-700
                        @elseif($status === 'commission_paid') bg-green-100 text-green-700
                        @elseif($status === 'completed') bg-gray-100 text-gray-700
                        @endif">
                        {{ ucfirst(str_replace('_', ' ', $status)) }}
                    </span>
                </div>

                <h1 class="text-2xl font-bold text-gray-900 mb-4">
                    {{ $match->tuitionOffer->title ?? 'General Tutoring Request' }}
                </h1>

                @if($type === 'application' && $match->cover_letter)
                    <div class="mb-4">
                        <h3 class="text-sm font-medium text-gray-700 mb-2">Tutor's Application Message:</h3>
                        <p class="text-gray-600 bg-gray-50 p-4 rounded">{{ $match->cover_letter }}</p>
                    </div>
                @endif

                @if($type === 'invitation')
                    <div class="mb-4">
                        <h3 class="text-sm font-medium text-gray-700 mb-2">Parent's Invitation Message:</h3>
                        <p class="text-gray-600 bg-gray-50 p-4 rounded">{{ $match->message }}</p>
                    </div>
                    @if($match->tutor_response_message)
                        <div class="mb-4">
                            <h3 class="text-sm font-medium text-gray-700 mb-2">Tutor's Response:</h3>
                            <p class="text-gray-600 bg-gray-50 p-4 rounded">{{ $match->tutor_response_message }}</p>
                        </div>
                    @endif
                @endif

                @if($match->tuitionOffer)
                    <div class="border-t border-gray-200 pt-4 mt-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Job Details</h3>
                        <div class="space-y-2 text-sm">
                            <p><strong>Subjects:</strong> {{ implode(', ', (array) $match->tuitionOffer->subjects) }}</p>
                            <p><strong>Class/Level:</strong> {{ $match->tuitionOffer->student_class }}</p>
                            <p><strong>Tuition Type:</strong> {{ ucfirst($match->tuitionOffer->tuition_type) }}</p>
                            <p><strong>Area:</strong> {{ $match->tuitionOffer->area?->name }}</p>
                            <p><strong>Salary:</strong> {{ number_format($match->tuitionOffer->salary) }} BDT/month</p>
                            @if($match->tuitionOffer->description)
                                <div class="mt-3">
                                    <strong>Description:</strong>
                                    <p class="text-gray-600 mt-1">{{ $match->tuitionOffer->description }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <!-- Timeline -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Match Timeline</h2>
                <div class="space-y-4">
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-2 bg-green-500 rounded"></div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">Match Created</p>
                            <p class="text-xs text-gray-500">{{ $match->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                    </div>

                    @if($match->sent_to_admin_at ?? $match->accepted_at)
                        <div class="flex gap-4">
                            <div class="flex-shrink-0 w-2 bg-green-500 rounded"></div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">Sent to Admin</p>
                                <p class="text-xs text-gray-500">{{ ($match->sent_to_admin_at ?? $match->accepted_at)->format('M d, Y h:i A') }}</p>
                            </div>
                        </div>
                    @endif

                    @if($type === 'application' && $match->admin_contacted_at)
                        <div class="flex gap-4">
                            <div class="flex-shrink-0 w-2 bg-green-500 rounded"></div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">Tutor Contacted</p>
                                <p class="text-xs text-gray-500">{{ $match->admin_contacted_at->format('M d, Y h:i A') }}</p>
                            </div>
                        </div>
                    @endif

                    @if($type === 'invitation' && in_array($match->status, ['contacted', 'negotiating', 'commission_paid', 'completed']))
                        <div class="flex gap-4">
                            <div class="flex-shrink-0 w-2 bg-green-500 rounded"></div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">Tutor Contacted</p>
                                <p class="text-xs text-gray-500">Status: {{ ucfirst($match->status) }}</p>
                            </div>
                        </div>
                    @endif

                    @if($commission)
                        <div class="flex gap-4">
                            <div class="flex-shrink-0 w-2 bg-green-500 rounded"></div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">Commission Paid</p>
                                <p class="text-xs text-gray-500">{{ $commission->paid_at->format('M d, Y h:i A') }}</p>
                                <p class="text-xs text-gray-600 mt-1">Amount: {{ $commission->formatted_amount }}</p>
                            </div>
                        </div>
                    @endif

                    @if(($type === 'application' && $match->parent_contact_shared_at) || ($type === 'invitation' && $match->status === 'completed'))
                        <div class="flex gap-4">
                            <div class="flex-shrink-0 w-2 bg-green-500 rounded"></div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">Contact Details Shared</p>
                                <p class="text-xs text-gray-500">
                                    {{ $type === 'application' ? $match->parent_contact_shared_at->format('M d, Y h:i A') : 'Completed' }}
                                </p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Admin Notes -->
            @if($type === 'application')
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Admin Notes</h2>
                    <form method="POST" action="{{ route('admin.matches.update-notes', ['type' => $type, 'id' => $match->id]) }}">
                        @csrf
                        <textarea name="admin_notes" rows="4"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Add notes about this match...">{{ $match->admin_notes }}</textarea>
                        <button type="submit" class="mt-3 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            Save Notes
                        </button>
                    </form>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            @if($quality)
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between mb-3">
                        <h2 class="text-lg font-semibold text-gray-900">Match quality</h2>
                        <span class="text-2xl font-bold {{ $quality->score >= 70 ? 'text-green-700' : ($quality->score >= 45 ? 'text-amber-700' : 'text-red-700') }}">{{ $quality->score }}<span class="text-sm text-gray-500">/100</span></span>
                    </div>
                    <div class="space-y-2 text-sm">
                        @foreach($quality->parts as $part)
                            <div class="flex items-center gap-2">
                                <span class="w-24 text-gray-600">{{ $part['label'] }}</span>
                                <div class="flex-1 h-2 bg-gray-100 rounded"><div class="h-2 rounded bg-teal-500" style="width: {{ $part['max'] ? round(100 * $part['points'] / $part['max']) : 0 }}%"></div></div>
                                <span class="w-12 text-right text-gray-700">{{ $part['points'] }}/{{ $part['max'] }}</span>
                            </div>
                        @endforeach
                    </div>
                    @if($quality->concerns)
                        <ul class="mt-3 text-sm text-amber-800 list-disc pl-5">
                            @foreach($quality->concerns as $concern)<li>{{ $concern }}</li>@endforeach
                        </ul>
                    @endif
                    <p class="mt-3 text-xs text-gray-500">Computed from the offer and the tutor's profile (subjects, class, area, budget, experience, verification).</p>
                </div>
            @endif
            <!-- Tutor Info -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Tutor Information</h2>
                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-gray-600">Name</p>
                        <p class="font-medium text-gray-900">{{ $match->tutor->name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Email</p>
                        <p class="font-medium text-gray-900">{{ $match->tutor->email }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Phone</p>
                        <p class="font-medium text-gray-900">{{ $match->tutor->tutorProfile?->phone ?? 'N/A' }}</p>
                    </div>
                    @if($match->tutor->tutorProfile)
                        <div>
                            <p class="text-gray-600">Experience</p>
                            <p class="font-medium text-gray-900">{{ $match->tutor->tutorProfile->experience_years }} years</p>
                        </div>
                        <div>
                            <p class="text-gray-600">Expected Fee</p>
                            <p class="font-medium text-gray-900">{{ number_format($match->tutor->tutorProfile->hourly_rate) }} BDT/month</p>
                        </div>
                    @endif
                    <div class="pt-3">
                        <a href="{{ route('tutors.show', $match->tutor->tutor_code ?? $match->tutor->id) }}" target="_blank"
                           class="text-blue-600 hover:text-blue-800 text-sm">
                            View Full Profile →
                        </a>
                    </div>
                </div>
            </div>

            <!-- Parent Info -->
            @if($parent)
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Parent Information</h2>
                    <div class="space-y-3 text-sm">
                        <div>
                            <p class="text-gray-600">Name</p>
                            <p class="font-medium text-gray-900">{{ $parent->name }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">Email</p>
                            <p class="font-medium text-gray-900">{{ $parent->email }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">Phone</p>
                            <p class="font-medium text-gray-900">{{ $parent->phone ?? 'N/A' }}</p>
                        </div>
                    </div>

                    @if($status === 'completed' || ($type === 'application' && $match->parent_contact_shared_at))
                        <div class="mt-4 p-3 bg-green-50 border border-green-200 rounded">
                            <p class="text-xs text-green-700 font-medium">✓ Contact details shared with tutor</p>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Actions -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Actions</h2>
                <div class="space-y-3">
                    @if($status === 'with_admin')
                        <form method="POST" action="{{ route('admin.matches.mark-contacted', ['type' => $type, 'id' => $match->id]) }}">
                            @csrf
                            <button type="submit" class="w-full px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition">
                                Mark as Contacted
                            </button>
                        </form>
                    @endif

                    @if($type === 'invitation' && $status === 'contacted')
                        <form method="POST" action="{{ route('admin.matches.mark-negotiating', ['type' => $type, 'id' => $match->id]) }}">
                            @csrf
                            <button type="submit" class="w-full px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition">
                                Mark as Negotiating
                            </button>
                        </form>
                    @endif

                    @if(in_array($status, ['contacted', 'negotiating']) && !$commission)
                        <button type="button" onclick="document.getElementById('commissionModal').classList.remove('hidden')"
                                class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                            Record Commission
                        </button>
                    @endif

                    @if($commission && $status === 'commission_paid')
                        <form method="POST" action="{{ route('admin.matches.share-contact', ['type' => $type, 'id' => $match->id]) }}"
                              onsubmit="return confirm('Are you sure you want to share parent contact details with the tutor? This action will notify the tutor.')">
                            @csrf
                            <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                Share Contact Details
                            </button>
                        </form>
                    @endif

                    @if($status === 'completed')
                        <div class="p-3 bg-gray-50 border border-gray-200 rounded text-center">
                            <p class="text-sm text-gray-600">✓ This match is completed</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Commission Info -->
            @if($commission)
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Commission Details</h2>
                    <div class="space-y-3 text-sm">
                        <div>
                            <p class="text-gray-600">Amount</p>
                            <p class="font-medium text-gray-900 text-lg">{{ $commission->formatted_amount }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">Payment Method</p>
                            <p class="font-medium text-gray-900">{{ ucfirst($commission->payment_method) }}</p>
                        </div>
                        @if($commission->receipt_number)
                            <div>
                                <p class="text-gray-600">Receipt Number</p>
                                <p class="font-medium text-gray-900">{{ $commission->receipt_number }}</p>
                            </div>
                        @endif
                        <div>
                            <p class="text-gray-600">Paid At</p>
                            <p class="font-medium text-gray-900">{{ $commission->paid_at->format('M d, Y h:i A') }}</p>
                        </div>
                        @if($commission->admin_notes)
                            <div>
                                <p class="text-gray-600">Notes</p>
                                <p class="font-medium text-gray-900">{{ $commission->admin_notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Commission Modal -->
<div id="commissionModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-lg bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Record Commission Payment</h3>
            <button onclick="document.getElementById('commissionModal').classList.add('hidden')"
                    class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <form method="POST" action="{{ route('admin.matches.record-commission', ['type' => $type, 'id' => $match->id]) }}">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Amount (BDT)</label>
                    <input type="number" name="amount" step="0.01" min="0" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Payment Method</label>
                    <select name="payment_method" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select method...</option>
                        <option value="cash">Cash</option>
                        <option value="bkash">bKash</option>
                        <option value="nagad">Nagad</option>
                        <option value="bank_transfer">Bank Transfer</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Receipt Number (Optional)</label>
                    <input type="text" name="receipt_number"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Notes (Optional)</label>
                    <textarea name="admin_notes" rows="3"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>
                <div class="flex gap-3 pt-4">
                    <button type="submit" class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                        Record Payment
                    </button>
                    <button type="button" onclick="document.getElementById('commissionModal').classList.add('hidden')"
                            class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                        Cancel
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
