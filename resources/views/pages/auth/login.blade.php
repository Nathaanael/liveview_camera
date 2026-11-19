@extends('layouts.fullscreen-layout')

@section('content')
    <div class="flex min-h-screen items-center justify-center bg-gray-100 px-4 py-10 dark:bg-gray-900">
        <div class="grid w-full max-w-5xl overflow-hidden rounded-3xl bg-white shadow-theme-lg lg:grid-cols-2 dark:bg-gray-800">

            {{-- Form --}}
            <div class="flex flex-col justify-center px-6 py-12 sm:px-16 lg:min-h-[600px]">
                <div class="mb-10 text-center">
                    <img src="/assets/images/logo/Indofood_CBP.svg.webp" alt="Indofood CBP" width="220" height="37"
                        class="mx-auto h-auto w-[220px]" />
                    <p class="mt-3 text-sm font-semibold text-indofood-500 dark:text-indofood-300">
                        {{ __('app.subtitle') }}
                    </p>
                </div>

                <div class="mb-8 text-center">
                    <h1 class="text-title-sm font-semibold text-gray-800 dark:text-white/90">
                        {{ __('login.title') }}
                    </h1>
                    <p class="mt-3 text-sm text-gray-500 dark:text-gray-400">
                        {{ __('login.subtitle') }}
                    </p>
                </div>

                <form method="POST" action="{{ route('login.store') }}" class="space-y-4" x-data="{ showPassword: false, submitting: false }"
                    @submit="submitting = true">
                    @csrf

                    <div>
                        <label for="email" class="sr-only">{{ __('login.emailLabel') }}</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                            autocomplete="username" placeholder="{{ __('login.emailPlaceholder') }}"
                            @class([
                                'h-12 w-full rounded-full border bg-transparent px-5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:outline-hidden focus:ring-3 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30',
                                'border-gray-300 focus:border-indofood-300 focus:ring-indofood-500/10 dark:border-gray-700 dark:focus:border-indofood-400' => ! $errors->has('email'),
                                'border-error-500 focus:border-error-300 focus:ring-error-500/10 dark:border-error-500' => $errors->has('email'),
                            ]) />
                        @error('email')
                            <p class="mt-1.5 ps-5 text-theme-xs text-error-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="sr-only">{{ __('login.passwordLabel') }}</label>
                        <div class="relative">
                            <input id="password" :type="showPassword ? 'text' : 'password'" type="password" name="password"
                                required autocomplete="current-password" placeholder="{{ __('login.passwordPlaceholder') }}"
                                @class([
                                    'h-12 w-full rounded-full border bg-transparent ps-5 pe-12 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:outline-hidden focus:ring-3 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30',
                                    'border-gray-300 focus:border-indofood-300 focus:ring-indofood-500/10 dark:border-gray-700 dark:focus:border-indofood-400' => ! $errors->has('password'),
                                    'border-error-500 focus:border-error-300 focus:ring-error-500/10 dark:border-error-500' => $errors->has('password'),
                                ]) />
                            <button type="button" @click="showPassword = !showPassword"
                                :aria-label="showPassword ? @js(__('login.hidePassword')) : @js(__('login.showPassword'))"
                                class="absolute end-4 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                                <x-svg.eye x-show="!showPassword" class="size-5 fill-current" />
                                <x-svg.eye-close x-show="showPassword" x-cloak class="size-5 fill-current" />
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1.5 ps-5 text-theme-xs text-error-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-4 text-center">
                        <button type="submit" :disabled="submitting"
                            class="inline-flex h-12 w-full max-w-56 items-center justify-center rounded-full bg-indofood-500 px-6 text-sm font-semibold tracking-wide text-white uppercase shadow-theme-xs transition hover:bg-indofood-600 focus:outline-hidden focus:ring-3 focus:ring-indofood-500/30 disabled:opacity-70 dark:bg-indofood-400 dark:hover:bg-indofood-500">
                            {{ __('login.submit') }}
                        </button>
                    </div>
                </form>

                <p class="mt-6 text-center text-theme-xs text-gray-500 dark:text-gray-400">
                    {{ __('login.forgot') }}
                </p>
            </div>

            {{-- Welcome panel --}}
            <div class="hidden items-center justify-center bg-indofood-500 px-12 py-16 text-center rounded-s-[220px] lg:flex dark:bg-indofood-700">
                <div class="max-w-sm">
                    <h2 class="text-title-md font-bold text-white">
                        {{ __('login.welcomeTitle') }}
                    </h2>
                    <p class="mt-5 text-lg leading-relaxed text-white/90">
                        {{ __('login.welcomeText') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
