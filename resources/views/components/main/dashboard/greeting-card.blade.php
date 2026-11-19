@props(['name' => ''])

@php
    $now = now('Asia/Jakarta')->locale('id');
    $hour = (int) $now->format('G');
    $period = match (true) {
        $hour >= 4 && $hour < 11 => 'morning',
        $hour >= 11 && $hour < 15 => 'afternoon',
        $hour >= 15 && $hour < 18 => 'evening',
        default => 'night',
    };
    $greetings = [
        'morning' => __('dashboard.greeting.morning'),
        'afternoon' => __('dashboard.greeting.afternoon'),
        'evening' => __('dashboard.greeting.evening'),
        'night' => __('dashboard.greeting.night'),
    ];
@endphp

<div {{ $attributes->class('relative overflow-hidden rounded-2xl border border-gray-200 bg-white p-6 sm:p-8 dark:border-gray-800 dark:bg-white/3') }}
    x-data="{
        greetings: @js($greetings),
        date: @js($now->translatedFormat('l, j F Y')),
        time: @js($now->format('H:i')),
        period: @js($period),
        tick() {
            const now = new Date();
            const tz = 'Asia/Jakarta';
            this.date = new Intl.DateTimeFormat('id-ID', { timeZone: tz, weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' }).format(now);
            this.time = new Intl.DateTimeFormat('id-ID', { timeZone: tz, hour: '2-digit', minute: '2-digit', hourCycle: 'h23' }).format(now).replace('.', ':');
            const hour = Number(new Intl.DateTimeFormat('en-US', { timeZone: tz, hour: 'numeric', hourCycle: 'h23' }).format(now));
            this.period = hour >= 4 && hour < 11 ? 'morning' : hour >= 11 && hour < 15 ? 'afternoon' : hour >= 15 && hour < 18 ? 'evening' : 'night';
        },
    }"
    x-init="tick(); setInterval(() => tick(), 15000)">

    <div class="pointer-events-none absolute end-0 top-0 w-full max-w-[250px] opacity-60 xl:max-w-[350px] dark:opacity-20">
        <img src="/assets/images/shape/grid-01.svg" alt="" aria-hidden="true" class="rtl:-scale-x-100" />
    </div>

    <div class="relative flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <span class="inline-flex items-center gap-2 rounded-full border border-gray-200 bg-white px-3.5 py-1.5 text-theme-xs font-medium text-gray-700 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
                <x-svg.sun class="size-4 text-gray-500 dark:text-gray-400" />
                <span x-text="date">{{ $now->translatedFormat('l, j F Y') }}</span>
            </span>

            <p class="mt-5 text-xl font-medium text-gray-500 dark:text-gray-400" x-text="greetings[period]">{{ $greetings[$period] }}</p>
            <h1 class="mt-1 text-title-sm font-bold text-gray-800 dark:text-white/90">{{ $name }}</h1>
            <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">{{ __('dashboard.greeting.welcome') }}</p>
        </div>

        <div class="flex min-w-[220px] items-baseline justify-end gap-2 self-start rounded-2xl border border-gray-200 bg-gray-50 px-6 py-5 sm:self-center dark:border-gray-800 dark:bg-gray-900">
            <span class="text-xl font-bold text-gray-800 tabular-nums dark:text-white/90" x-text="time">{{ $now->format('H:i') }}</span>
            <span class="text-theme-xs font-medium text-gray-500 dark:text-gray-400">{{ __('dashboard.greeting.timezone') }}</span>
        </div>
    </div>
</div>
