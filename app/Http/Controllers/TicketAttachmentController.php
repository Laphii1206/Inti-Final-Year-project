<?php

namespace App\Http\Controllers;

use App\Models\TicketAttachment;
use Illuminate\Support\Facades\Storage;

class TicketAttachmentController extends Controller
{
    public function download(TicketAttachment $attachment)
    {
        $ticket = $attachment->ticket;

        $user = auth()->user();
        if (!$user) abort(401);
        if (!$user->isAdmin() && $ticket->user_id !== $user->id) abort(403);

        if (!Storage::disk('public')->exists($attachment->file_path)) {
            abort(404, 'Attachment not found.');
        }

        return Storage::disk('public')->download(
            $attachment->file_path,
            $attachment->original_filename
        );
    }
}
