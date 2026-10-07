<?php
namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EventImageStorage
{
    public function store(UploadedFile $file): string
    {
        $id = (string) Str::uuid();
        DB::table('event_images')->insert([
            'id' => $id,
            'mime_type' => $file->getMimeType(),
            'content_base64' => base64_encode(file_get_contents($file->getRealPath())),
            'created_at' => now(),
        ]);
        return 'db:'.$id;
    }

    public function delete(?string $path): void
    {
        if (!$path) return;
        if (str_starts_with($path, 'db:')) {
            DB::table('event_images')->where('id', substr($path, 3))->delete();
        } else {
            Storage::disk('public')->delete($path);
        }
    }
}
