<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('support.ticket.{ticketId}', function ($user, $ticketId) {
    if (! empty($user->is_admin) || (method_exists($user, 'hasRole') && ($user->hasRole('admin') || $user->hasRole('administrator')))) {
        return true;
    }

    return \App\Models\SupportTicket::query()
        ->whereKey($ticketId)
        ->where('user_id', $user->id)
        ->exists();
});

Broadcast::channel('admin.support', function ($user) {
    if (! empty($user->is_admin)) {
        return true;
    }

    return method_exists($user, 'hasRole')
        && ($user->hasRole('admin') || $user->hasRole('administrator'));
});