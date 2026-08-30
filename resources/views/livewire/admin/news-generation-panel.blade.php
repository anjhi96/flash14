<?php

use App\Jobs\GenerateNewsArticlesJob;
use App\Models\NewsGenerationRun;
use Livewire\Volt\Component;

new class extends Component {
    public ?string $dispatchMessage = null;

    public function generateNow(): void
    {
        GenerateNewsArticlesJob::dispatch(null, 1, 'manual');

        $this->dispatchMessage = 'Proses generate artikel sudah masuk antrean. Pastikan queue worker (php artisan queue:work) sedang berjalan agar segera diproses — riwayat di bawah akan otomatis diperbarui.';
    }

    public function with(): array
    {
        $runs = NewsGenerationRun::latest('started_at')->take(15)->get();

        return [
            'runs' => $runs,
            'hasRunning' => $runs->contains('status', 'running'),
        ];
    }
}; ?>

<div class="space-y-4" @if ($hasRunning) wire:poll.5s @endif>
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h3 class="text-lg font-bold text-on-surface">Auto-Blog</h3>
            <p class="text-xs text-on-surface-variant">Jalankan generate artikel secara manual & pantau riwayat run otomatis.</p>
        </div>
        <x-primary-button type="button" wire:click="generateNow" wire:loading.attr="disabled" wire:target="generateNow">
            <span class="material-symbols-outlined text-[16px]" wire:loading.remove wire:target="generateNow">bolt</span>
            <span wire:loading wire:target="generateNow">Mengirim...</span>
            <span wire:loading.remove wire:target="generateNow">Generate Sekarang</span>
        </x-primary-button>
    </div>

    @if ($dispatchMessage)
        <x-alert variant="info">{{ $dispatchMessage }}</x-alert>
    @endif

    <div class="bg-surface-container-lowest rounded-xl border border-outline-variant overflow-hidden">
        <div class="px-5 py-4 border-b border-outline-variant">
            <h4 class="text-sm font-bold text-on-surface uppercase tracking-wider">Riwayat Run Terakhir</h4>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-surface-container text-on-surface-variant uppercase tracking-wider border-b border-outline-variant font-bold">
                    <tr>
                        <th class="py-3 px-4">Waktu</th>
                        <th class="py-3 px-4 w-24">Dipicu Oleh</th>
                        <th class="py-3 px-4 w-24 text-right">Item Diambil</th>
                        <th class="py-3 px-4 w-28 text-right">Artikel Dibuat</th>
                        <th class="py-3 px-4 w-24">Status</th>
                        <th class="py-3 px-4">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    @forelse ($runs as $run)
                        <tr class="hover:bg-surface-container transition-colors">
                            <td class="py-3 px-4 text-on-surface-variant whitespace-nowrap">{{ $run->started_at->format('d M Y, H:i') }}</td>
                            <td class="py-3 px-4">
                                <x-badge>{{ $run->triggered_by === 'manual' ? 'Manual' : 'Terjadwal' }}</x-badge>
                            </td>
                            <td class="py-3 px-4 text-right font-mono text-on-surface-variant">{{ $run->items_fetched }}</td>
                            <td class="py-3 px-4 text-right font-mono text-on-surface-variant">{{ $run->articles_created }}</td>
                            <td class="py-3 px-4">
                                <x-badge :variant="match($run->status) { 'success' => 'success', 'failed' => 'error', default => 'primary' }">
                                    {{ strtoupper($run->status) }}
                                </x-badge>
                            </td>
                            <td class="py-3 px-4 text-on-surface-variant max-w-sm truncate" title="{{ $run->error_message }}">
                                {{ $run->error_message ?: '—' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-on-surface-variant">Belum ada riwayat run.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
