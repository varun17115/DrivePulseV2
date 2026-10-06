@extends('layouts.admin')

@section('title', 'My Notifications')
@section('page_title', 'All Notifications')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Notifications</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent py-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold">
                    <i class="fa-solid fa-bell text-primary me-2"></i>Notifications
                </h6>
                @if($notifications->where('is_read', false)->count() > 0)
                    <form action="{{ route('notifications.markAllRead') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-primary rounded-pill">
                            <i class="fa-solid fa-check-double me-1"></i> Mark All as Read
                        </button>
                    </form>
                @endif
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @forelse($notifications as $notification)
                        <div class="list-group-item p-3 d-flex align-items-start gap-3 {{ $notification->is_read ? 'bg-white' : 'bg-light' }}">
                            <div class="icon-circle bg-{{ $notification->type }}-subtle text-{{ $notification->type }} flex-shrink-0 mt-1">
                                @if($notification->type === 'success')
                                    <i class="fa-solid fa-circle-check"></i>
                                @elseif($notification->type === 'warning')
                                    <i class="fa-solid fa-triangle-exclamation"></i>
                                @elseif($notification->type === 'danger')
                                    <i class="fa-solid fa-circle-xmark"></i>
                                @else
                                    <i class="fa-solid fa-info"></i>
                                @endif
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <h6 class="mb-0 fw-bold text-dark fs-7">{{ $notification->title }}</h6>
                                    <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                </div>
                                <p class="mb-2 text-muted fs-8">{{ $notification->message }}</p>
                                <div class="d-flex gap-2">
                                    @if($notification->action_url)
                                        <a href="{{ $notification->action_url }}" class="btn btn-sm btn-primary rounded-pill px-3 py-1 fs-8">
                                            View Details
                                        </a>
                                    @endif
                                    @if(!$notification->is_read)
                                        <form action="{{ route('notifications.markRead', $notification->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-light border rounded-pill px-3 py-1 fs-8">
                                                Mark as Read
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5 text-muted">
                            <i class="fa-regular fa-bell-slash fa-3x mb-3 text-secondary"></i>
                            <p class="mb-0">No notifications found.</p>
                        </div>
                    @endforelse
                </div>
            </div>
            @if($notifications->hasPages())
                <div class="card-footer bg-transparent py-3">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
