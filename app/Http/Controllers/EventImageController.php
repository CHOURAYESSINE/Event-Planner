<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class EventImageController extends Controller
{
    public function show(string $id)
    {
        $image = DB::table('event_images')->where('id', $id)->first();
        abort_unless($image, 404);
        return response(base64_decode($image->content_base64), 200, [
            'Content-Type' => $image->mime_type,
            'Cache-Control' => 'public, max-age=31536000, immutable',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }
}
