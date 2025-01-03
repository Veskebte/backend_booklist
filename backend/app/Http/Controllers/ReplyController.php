<?php

namespace App\Http\Controllers;

use App\Models\Reply;
use App\Models\Like;
use Illuminate\Http\Request;

class ReplyController extends Controller
{
    public function index($bookId) {
        $replies = Reply::where('book_id', $bookId)
            ->with('user:id,name')
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json($replies);
    }

    public function store(Request $request) {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'content' => 'required'
        ]);

        $reply = Reply::create([
            'user_id' => auth()->id(),
            'book_id' => $request->book_id,
            'content' => $request->content,
        ]);

        return response()->json($reply, 201);
    }

    public function show($id) {
        $reply = Reply::withCount('likes')->findOrFail($id);
        return response()->json([
            'reply' => $reply,
        ]);
    }

    public function update(Request $request, $id) {
        $reply = Reply::find($id);

        if (!$reply) {
            return response()->json(['message' => 'Komentar tidak ditemukan'], 404);
        }

        if ($reply->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'content' => 'required',
        ]);

        $reply->update([
            'content' => $request->content,
        ]);

        return response()->json($reply);
    }

    public function destroy($id) {
        $reply = Reply::find($id);

        if (!$reply) {
            return response()->json(['message' => 'Komentar tidak ditemukan'], 404);
        }

        if ($reply->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $reply->delete();
        return response()->json(['message' => 'Reply deleted successfully']);
    }

    public function like(Request $request, $id) {
        $reply = Reply::findOrFail($id);
        $user = $request->user();

        if ($user->likes()->where('reply_id', $reply->id)->exists()) {
            return response()->json(['message' => 'Anda sudah menyukai komentar ini']. 400);
        }

        $user->likes()->create(['reply_id' => $reply->id]);

        return response()->json(['message' => 'Liked successfully']);
    }

    public function unlike($id) {
        $like = Like::where('user_id', auth()->id())->where('reply_id', $id)->first();

        if (!$like) {
            return response()->json(['message' => 'Anda belum menyukai komentar ini'], 400);
        }

        $like->delete();

        return response()->json(['message' => 'Unliked successfully']);
    }
}
