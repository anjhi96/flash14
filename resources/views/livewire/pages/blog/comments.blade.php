<?php

use App\Models\Comment;
use Livewire\Volt\Component;

new class extends Component {
    public int $postId;

    public string $name = '';
    public string $email = '';
    public string $comment = '';
    public string $website = ''; // honeypot

    public bool $submitted = false;

    public ?int $replyingTo = null;
    public string $replyName = '';
    public string $replyEmail = '';
    public string $replyComment = '';
    public string $replyWebsite = ''; // honeypot

    public function mount(int $postId): void
    {
        $this->postId = $postId;
    }

    public function submitComment(): void
    {
        if (filled($this->website)) {
            // Pretend it worked so the bot doesn't learn to avoid this field.
            $this->reset(['name', 'email', 'comment', 'website']);
            $this->submitted = true;

            return;
        }

        $validated = $this->validate([
            'name' => 'required|string|min:2|max:255',
            'email' => 'required|email|max:255',
            'comment' => 'required|string|min:3|max:2000',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'name.min' => 'Nama minimal :min karakter.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format alamat email tidak valid.',
            'comment.required' => 'Komentar tidak boleh kosong.',
            'comment.min' => 'Komentar minimal :min karakter.',
        ]);

        Comment::create([
            'post_id' => $this->postId,
            'name' => trim(strip_tags($validated['name'])),
            'email' => trim($validated['email']),
            'comment' => trim(strip_tags($validated['comment'])),
            'status' => 'pending', // public submissions always start pending for moderation
        ]);

        $this->reset(['name', 'email', 'comment', 'website']);
        $this->submitted = true;
    }

    public function startReply(int $id): void
    {
        $this->replyingTo = $id;
        $this->reset(['replyName', 'replyEmail', 'replyComment', 'replyWebsite']);
        $this->resetValidation();
    }

    public function cancelReply(): void
    {
        $this->replyingTo = null;
    }

    public function submitReply(): void
    {
        if (filled($this->replyWebsite)) {
            $this->replyingTo = null;

            return;
        }

        $validated = $this->validate([
            'replyName' => 'required|string|min:2|max:255',
            'replyEmail' => 'required|email|max:255',
            'replyComment' => 'required|string|min:3|max:2000',
        ], [
            'replyName.required' => 'Nama wajib diisi.',
            'replyEmail.required' => 'Alamat email wajib diisi.',
            'replyEmail.email' => 'Format alamat email tidak valid.',
            'replyComment.required' => 'Balasan tidak boleh kosong.',
        ]);

        Comment::create([
            'post_id' => $this->postId,
            'parent_id' => $this->replyingTo,
            'name' => trim(strip_tags($validated['replyName'])),
            'email' => trim($validated['replyEmail']),
            'comment' => trim(strip_tags($validated['replyComment'])),
            'status' => 'pending',
        ]);

        $this->replyingTo = null;
        $this->reset(['replyName', 'replyEmail', 'replyComment', 'replyWebsite']);
    }

    public function with(): array
    {
        return [
            'comments' => Comment::where('post_id', $this->postId)
                ->approved()
                ->root()
                ->with(['replies' => fn ($q) => $q->approved()->oldest()])
                ->latest()
                ->get(),
        ];
    }
}; ?>

<div class="space-y-6">
    <h3 class="text-base font-bold text-on-surface">Diskusi ({{ $comments->count() }})</h3>

    @if ($submitted)
        <x-alert variant="success" :dismissible="false">
            Terima kasih! Komentar Anda telah terkirim dan sedang menunggu moderasi sebelum tampil di halaman ini.
        </x-alert>
    @endif

    <!-- New Comment Form -->
    <form wire:submit="submitComment" class="relative bg-surface-container-lowest border border-outline-variant rounded-xl p-4 space-y-3 text-xs">
        <!-- Honeypot -->
        <div class="absolute left-[-9999px] top-0" aria-hidden="true">
            <label for="comment-website">Biarkan kosong</label>
            <input type="text" id="comment-website" wire:model="website" tabindex="-1" autocomplete="off">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
                <x-input-label value="Nama *" />
                <x-text-input type="text" wire:model="name" placeholder="Nama Anda" />
                @error('name') <span class="text-[11px] text-state-error font-semibold mt-1 block">{{ $message }}</span> @enderror
            </div>
            <div>
                <x-input-label value="Email *" />
                <x-text-input type="email" wire:model="email" placeholder="nama@email.com" />
                @error('email') <span class="text-[11px] text-state-error font-semibold mt-1 block">{{ $message }}</span> @enderror
            </div>
        </div>
        <div>
            <x-input-label value="Komentar *" />
            <x-textarea-input wire:model="comment" :rows="3" placeholder="Tulis pendapat atau pertanyaan Anda..." />
            @error('comment') <span class="text-[11px] text-state-error font-semibold mt-1 block">{{ $message }}</span> @enderror
        </div>
        <x-primary-button type="submit">Kirim Komentar</x-primary-button>
    </form>

    <!-- Comment List -->
    <div class="space-y-4">
        @forelse ($comments as $c)
            <div class="border-b border-outline-variant pb-4 last:border-b-0">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full bg-surface-container flex items-center justify-center font-bold text-on-surface-variant text-xs shrink-0">
                        {{ substr($c->name, 0, 1) }}
                    </div>
                    <div>
                        <p class="text-xs font-bold text-on-surface">{{ $c->name }}</p>
                        <p class="text-[11px] text-on-surface-variant">{{ $c->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                <p class="text-xs text-on-surface mt-2 ml-10 leading-relaxed">{{ $c->comment }}</p>
                <button type="button" wire:click="startReply({{ $c->id }})" class="ml-10 mt-1.5 text-[11px] font-semibold text-primary hover:underline cursor-pointer">
                    Balas Komentar
                </button>

                @if ($replyingTo === $c->id)
                    <form wire:submit="submitReply" class="relative ml-10 mt-3 space-y-2 bg-surface-container p-3 rounded-lg">
                        <!-- Honeypot -->
                        <div class="absolute left-[-9999px] top-0" aria-hidden="true">
                            <label for="reply-website">Biarkan kosong</label>
                            <input type="text" id="reply-website" wire:model="replyWebsite" tabindex="-1" autocomplete="off">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                            <div>
                                <x-text-input type="text" wire:model="replyName" placeholder="Nama Anda" />
                                @error('replyName') <span class="text-[11px] text-state-error font-semibold block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <x-text-input type="email" wire:model="replyEmail" placeholder="Email Anda" />
                                @error('replyEmail') <span class="text-[11px] text-state-error font-semibold block">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <x-textarea-input wire:model="replyComment" :rows="2" placeholder="Tulis balasan Anda..." />
                        @error('replyComment') <span class="text-[11px] text-state-error font-semibold block">{{ $message }}</span> @enderror
                        <div class="flex gap-2">
                            <x-primary-button type="submit">Kirim Balasan</x-primary-button>
                            <x-secondary-button type="button" wire:click="cancelReply">Batal</x-secondary-button>
                        </div>
                    </form>
                @endif

                @if ($c->replies->isNotEmpty())
                    <div class="ml-10 mt-3 space-y-3 border-l-2 border-outline-variant pl-4">
                        @foreach ($c->replies as $reply)
                            <div>
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-surface-container flex items-center justify-center font-bold text-on-surface-variant text-[10px] shrink-0">
                                        {{ substr($reply->name, 0, 1) }}
                                    </div>
                                    <p class="text-xs font-bold text-on-surface">{{ $reply->name }}</p>
                                    @if ($reply->user_id)
                                        <x-badge variant="primary">TIM FLASHDEV</x-badge>
                                    @endif
                                    <p class="text-[11px] text-on-surface-variant">{{ $reply->created_at->diffForHumans() }}</p>
                                </div>
                                <p class="text-xs text-on-surface mt-1 ml-8 leading-relaxed">{{ $reply->comment }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @empty
            <p class="text-xs text-on-surface-variant">Belum ada komentar. Jadilah yang pertama berdiskusi di artikel ini.</p>
        @endforelse
    </div>
</div>
