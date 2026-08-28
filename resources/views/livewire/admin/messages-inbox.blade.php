<?php

use App\Models\ContactMessage;
use Livewire\Volt\Component;

new class extends Component {
    public ?ContactMessage $selectedMessage = null;
    public bool $showMessageModal = false;
    public ?string $successMessage = null;

    public function viewMessage(int $id): void
    {
        $msg = ContactMessage::findOrFail($id);
        if (! $msg->is_read) {
            $msg->is_read = true;
            $msg->save();
        }
        $this->selectedMessage = $msg;
        $this->showMessageModal = true;
    }

    public function toggleMessageRead(int $id): void
    {
        $msg = ContactMessage::findOrFail($id);
        $msg->is_read = ! $msg->is_read;
        $msg->save();
    }

    public function deleteMessage(int $id): void
    {
        ContactMessage::findOrFail($id)->delete();
        $this->showMessageModal = false;
        $this->successMessage = 'Pesan berhasil dihapus.';
    }

    public function with(): array
    {
        return [
            'messages' => ContactMessage::recent()->get(),
            'messagesUnreadCount' => ContactMessage::unread()->count(),
        ];
    }
}; ?>

<div class="space-y-4">
    @if ($successMessage)
        <x-alert variant="success">{{ $successMessage }}</x-alert>
    @endif

    <div>
        <h3 class="text-lg font-bold text-on-surface">Kotak Pesan Masuk</h3>
        <p class="text-xs text-on-surface-variant">{{ $messagesUnreadCount }} Pesan Belum Dibaca</p>
    </div>

    <div class="bg-surface-container-lowest rounded-xl border border-outline-variant overflow-hidden">
        @if ($messages->isEmpty())
            <div class="p-8 text-center text-on-surface-variant text-xs">Belum ada pesan masuk.</div>
        @else
            <div class="divide-y divide-outline-variant text-xs">
                @foreach ($messages as $msg)
                    <div class="p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3 {{ !$msg->is_read ? 'bg-primary-container/40' : '' }}">
                        <div class="space-y-1 cursor-pointer flex-1" wire:click="viewMessage({{ $msg->id }})">
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-on-surface text-xs">{{ $msg->name }}</span>
                                <span class="text-on-surface-variant">({{ $msg->email }})</span>
                                @if ($msg->phone)
                                    <span class="text-on-surface-variant">&bull; WA: {{ $msg->phone }}</span>
                                @endif
                                @if (!$msg->is_read)
                                    <x-badge variant="error">UNREAD</x-badge>
                                @endif
                            </div>
                            <p class="font-semibold text-primary">{{ $msg->subject ?: 'Tanpa Subjek' }}</p>
                            <p class="text-on-surface-variant line-clamp-1">{{ $msg->message }}</p>
                        </div>

                        <div class="flex items-center space-x-1 shrink-0">
                            <span class="text-[11px] text-on-surface-variant mr-2">{{ $msg->created_at->format('d M Y, H:i') }}</span>
                            <x-link-button wire:click="toggleMessageRead({{ $msg->id }})">
                                {{ $msg->is_read ? 'Tandai Belum Dibaca' : 'Tandai Dibaca' }}
                            </x-link-button>
                            <x-link-button variant="danger" wire:click="deleteMessage({{ $msg->id }})" wire:confirm="Hapus pesan ini?">Hapus</x-link-button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- MODAL: VIEW MESSAGE -->
    @if ($showMessageModal && $selectedMessage)
        <div x-data class="fixed inset-0 bg-on-surface/60 z-50 flex items-center justify-center p-4" wire:click.self="$set('showMessageModal', false)" x-on:keydown.escape.window="$wire.set('showMessageModal', false)">
            <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-6 max-w-lg w-full space-y-4 shadow-xl text-on-surface">
                <div class="flex items-center justify-between border-b border-outline-variant pb-3">
                    <h3 class="text-base font-bold text-on-surface">Detail Pesan Masuk</h3>
                    <button wire:click="$set('showMessageModal', false)" class="text-on-surface-variant hover:text-on-surface font-bold text-xl cursor-pointer" aria-label="Tutup">&times;</button>
                </div>
                <div class="space-y-3 text-xs">
                    <div>
                        <span class="text-on-surface-variant block font-semibold uppercase text-[10px]">Pengirim</span>
                        <span class="text-on-surface font-bold text-sm">{{ $selectedMessage->name }}</span>
                        <span class="text-on-surface-variant">({{ $selectedMessage->email }})</span>
                    </div>
                    @if ($selectedMessage->phone)
                        <div>
                            <span class="text-on-surface-variant block font-semibold uppercase text-[10px]">Nomor WhatsApp</span>
                            <span class="text-primary font-bold">{{ $selectedMessage->phone }}</span>
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $selectedMessage->phone) }}" target="_blank" class="ml-2 text-state-success underline font-semibold">Buka WhatsApp &#8599;</a>
                        </div>
                    @endif
                    <div>
                        <span class="text-on-surface-variant block font-semibold uppercase text-[10px]">Subjek</span>
                        <span class="text-on-surface font-semibold">{{ $selectedMessage->subject ?: 'Tanpa Subjek' }}</span>
                    </div>
                    <div>
                        <span class="text-on-surface-variant block font-semibold uppercase text-[10px] mb-1">Isi Pesan</span>
                        <div class="bg-surface-container p-3.5 rounded-lg text-on-surface leading-relaxed border border-outline-variant whitespace-pre-wrap">
                            {{ $selectedMessage->message }}
                        </div>
                    </div>
                    <div class="text-[10px] text-on-surface-variant pt-1">
                        Diterima: {{ $selectedMessage->created_at->format('d F Y, H:i:s') }}
                    </div>
                </div>
                <div class="flex justify-between items-center pt-3 border-t border-outline-variant">
                    <x-danger-button wire:click="deleteMessage({{ $selectedMessage->id }})" wire:confirm="Hapus pesan ini?">Hapus Pesan</x-danger-button>
                    <x-secondary-button wire:click="$set('showMessageModal', false)">Tutup</x-secondary-button>
                </div>
            </div>
        </div>
    @endif
</div>
