<?php

use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component {
    public string $statusFilter = 'pending';
    public ?int $replyingTo = null;
    public string $replyBody = '';
    public ?string $successMessage = null;

    public function setFilter(string $status): void
    {
        $this->statusFilter = $status;
    }

    public function approve(int $id): void
    {
        Comment::findOrFail($id)->update(['status' => 'approved']);
        $this->successMessage = 'Komentar berhasil disetujui.';
    }

    public function markSpam(int $id): void
    {
        Comment::findOrFail($id)->update(['status' => 'spam']);
        $this->successMessage = 'Komentar ditandai sebagai spam.';
    }

    public function delete(int $id): void
    {
        Comment::findOrFail($id)->delete();
        $this->replyingTo = null;
        $this->successMessage = 'Komentar berhasil dihapus.';
    }

    public function startReply(int $id): void
    {
        $this->replyingTo = $id;
        $this->replyBody = '';
        $this->resetValidation();
    }

    public function cancelReply(): void
    {
        $this->replyingTo = null;
        $this->replyBody = '';
    }

    public function submitReply(): void
    {
        $this->validate([
            'replyBody' => 'required|string|min:2',
        ], [
            'replyBody.required' => 'Balasan tidak boleh kosong.',
            'replyBody.min' => 'Balasan minimal :min karakter.',
        ]);

        $parent = Comment::findOrFail($this->replyingTo);
        $admin = Auth::user();

        // Trusted source (logged-in admin) — approved immediately, unlike
        // public submissions which always land as 'pending' first.
        Comment::create([
            'post_id' => $parent->post_id,
            'parent_id' => $parent->id,
            'user_id' => $admin->id,
            'name' => $admin->name,
            'email' => $admin->email,
            'comment' => $this->replyBody,
            'status' => 'approved',
        ]);

        $this->replyingTo = null;
        $this->replyBody = '';
        $this->successMessage = 'Balasan berhasil dikirim.';
    }

    public function with(): array
    {
        $query = Comment::with(['post', 'replies'])->root()->latest();

        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        return [
            'comments' => $query->get(),
            'pendingCount' => Comment::pending()->count(),
        ];
    }
}; ?>

<div class="space-y-4">
    @if ($successMessage)
        <x-alert variant="success">{{ $successMessage }}</x-alert>
    @endif

    <div>
        <h3 class="text-lg font-bold text-on-surface">Moderasi Komentar</h3>
        <p class="text-xs text-on-surface-variant">{{ $pendingCount }} komentar menunggu persetujuan.</p>
    </div>

    <div class="flex flex-wrap items-center gap-2">
        @foreach (['pending' => 'Pending', 'approved' => 'Approved', 'spam' => 'Spam', 'all' => 'Semua'] as $value => $label)
            <button
                type="button"
                wire:click="setFilter('{{ $value }}')"
                class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-all cursor-pointer {{ $statusFilter === $value ? 'bg-primary text-on-primary' : 'bg-surface-container-lowest text-on-surface-variant hover:bg-surface-container border border-outline' }}"
            >
                {{ $label }}
            </button>
        @endforeach
    </div>

    <div class="bg-surface-container-lowest rounded-xl border border-outline-variant divide-y divide-outline-variant">
        @forelse ($comments as $comment)
            <div class="p-4 space-y-2">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="font-bold text-on-surface text-xs">{{ $comment->name }}</span>
                            <span class="text-on-surface-variant text-xs">({{ $comment->email }})</span>
                            <x-badge :variant="match($comment->status) { 'approved' => 'success', 'spam' => 'error', default => 'neutral' }">
                                {{ strtoupper($comment->status) }}
                            </x-badge>
                        </div>
                        <p class="text-[11px] text-on-surface-variant mt-0.5">
                            pada artikel <span class="font-semibold text-primary">{{ $comment->post?->title ?? 'Artikel dihapus' }}</span>
                            &middot; {{ $comment->created_at->format('d M Y, H:i') }}
                        </p>
                        <p class="text-xs text-on-surface mt-2 leading-relaxed">{{ $comment->comment }}</p>
                    </div>
                    <div class="flex items-center gap-1 shrink-0">
                        @if ($comment->status !== 'approved')
                            <x-link-button wire:click="approve({{ $comment->id }})">Approve</x-link-button>
                        @endif
                        @if ($comment->status !== 'spam')
                            <x-link-button wire:click="markSpam({{ $comment->id }})">Spam</x-link-button>
                        @endif
                        <x-link-button wire:click="startReply({{ $comment->id }})">Balas</x-link-button>
                        <x-link-button variant="danger" wire:click="delete({{ $comment->id }})" wire:confirm="Hapus komentar ini beserta balasannya?">Hapus</x-link-button>
                    </div>
                </div>

                @if ($replyingTo === $comment->id)
                    <form wire:submit="submitReply" class="ml-4 pl-4 border-l-2 border-primary/40 space-y-2">
                        <x-textarea-input wire:model="replyBody" :rows="2" placeholder="Tulis balasan sebagai admin..." />
                        @error('replyBody') <span class="text-state-error font-semibold text-[11px] block">{{ $message }}</span> @enderror
                        <div class="flex gap-2">
                            <x-primary-button type="submit">Kirim Balasan</x-primary-button>
                            <x-secondary-button type="button" wire:click="cancelReply">Batal</x-secondary-button>
                        </div>
                    </form>
                @endif

                @if ($comment->replies->isNotEmpty())
                    <div class="ml-4 pl-4 border-l-2 border-outline-variant space-y-3">
                        @foreach ($comment->replies as $reply)
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span class="font-bold text-on-surface text-xs">{{ $reply->name }}</span>
                                        @if ($reply->user_id)
                                            <x-badge variant="primary">ADMIN</x-badge>
                                        @endif
                                        <x-badge :variant="match($reply->status) { 'approved' => 'success', 'spam' => 'error', default => 'neutral' }">
                                            {{ strtoupper($reply->status) }}
                                        </x-badge>
                                    </div>
                                    <p class="text-xs text-on-surface mt-1 leading-relaxed">{{ $reply->comment }}</p>
                                </div>
                                <div class="flex items-center gap-1 shrink-0">
                                    @if ($reply->status !== 'approved')
                                        <x-link-button wire:click="approve({{ $reply->id }})">Approve</x-link-button>
                                    @endif
                                    <x-link-button variant="danger" wire:click="delete({{ $reply->id }})" wire:confirm="Hapus balasan ini?">Hapus</x-link-button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @empty
            <div class="p-8 text-center text-on-surface-variant text-xs">Tidak ada komentar untuk filter ini.</div>
        @endforelse
    </div>
</div>
