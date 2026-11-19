@props([
    'cameras' => [],
    'go2rtcUrl',
    'playerMode' => 'webrtc,mse',
])

@php
    $streamUrl = fn (string $stream) => $go2rtcUrl.'/stream.html?'.http_build_query(['src' => $stream, 'mode' => $playerMode]);
@endphp

<div
    x-data="{ isOpen: false, name: '', url: '' }"
    @keydown.escape.window="isOpen = false"
>
    <x-common.component-card :title="__('Live View Test')" :desc="__('Grid uses the sub stream; click a camera to open the main stream.')">
        @if (count($cameras) === 0)
            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('No cameras configured.') }}</p>
        @else
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3">
                @foreach ($cameras as $camera)
                    <div class="overflow-hidden rounded-xl border border-gray-200 dark:border-gray-800">
                        <div class="relative aspect-video bg-gray-900">
                            <iframe
                                src="{{ $streamUrl($camera['stream'].'_sub') }}"
                                class="absolute inset-0 h-full w-full border-0"
                                allow="autoplay; fullscreen"
                                loading="lazy"
                                title="{{ $camera['name'] }}"
                            ></iframe>
                        </div>
                        <div class="flex items-center justify-between gap-2 bg-white px-4 py-3 dark:bg-white/[0.03]">
                            <span class="truncate text-sm font-medium text-gray-800 dark:text-white/90">{{ $camera['name'] }}</span>
                            <button
                                type="button"
                                class="shrink-0 rounded-lg bg-brand-500 px-3 py-1.5 text-theme-xs font-medium text-white hover:bg-brand-600"
                                @click="name = @js($camera['name']); url = @js($streamUrl($camera['stream'].'_main')); isOpen = true"
                            >
                                {{ __('Fullscreen') }}
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </x-common.component-card>

    {{-- Fullscreen viewer (main stream). x-if removes the iframe on close so the stream stops. --}}
    <template x-if="isOpen">
        <div class="fixed inset-0 z-999999 flex flex-col bg-gray-950/95 p-4">
            <div class="mb-3 flex items-center justify-between gap-2">
                <h3 class="truncate text-base font-medium text-white/90" x-text="name"></h3>
                <button
                    type="button"
                    class="rounded-lg border border-gray-700 px-3 py-1.5 text-sm text-white/90 hover:bg-white/10"
                    @click="isOpen = false"
                    x-init="$nextTick(() => $el.focus())"
                >
                    {{ __('Close') }}
                </button>
            </div>
            <iframe :src="url" class="w-full flex-1 rounded-xl border-0 bg-black" allow="autoplay; fullscreen" :title="name"></iframe>
        </div>
    </template>
</div>
