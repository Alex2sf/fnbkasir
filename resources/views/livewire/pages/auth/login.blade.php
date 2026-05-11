<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false));
    }
}; ?>

<div>
    <div class="mb-8">
        <h2 class="text-3xl font-black text-gray-900">Selamat datang 👋</h2>
        <p class="text-gray-500 mt-2 text-sm">Masuk ke akun kasir Anda untuk mulai melayani.</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="login" class="space-y-5">
        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5">Email</label>
            <input wire:model="form.email" id="email" type="email" name="email" required autofocus autocomplete="username"
                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition"
                placeholder="kasir@warunggalih.com">
            <x-input-error :messages="$errors->get('form.email')" class="mt-1.5" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-semibold text-gray-700 mb-1.5">Password</label>
            <input wire:model="form.password" id="password" type="password" name="password" required autocomplete="current-password"
                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition"
                placeholder="••••••••">
            <x-input-error :messages="$errors->get('form.password')" class="mt-1.5" />
        </div>

        <!-- Remember + Forgot -->
        <div class="flex items-center justify-between pt-1">
            <label class="flex items-center gap-2 cursor-pointer">
                <input wire:model="form.remember" type="checkbox" id="remember"
                    class="w-4 h-4 rounded border-gray-300 text-orange-500 focus:ring-orange-500">
                <span class="text-sm text-gray-600">Ingat saya</span>
            </label>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" wire:navigate
                    class="text-sm text-orange-500 hover:text-orange-600 font-medium transition">
                    Lupa password?
                </a>
            @endif
        </div>

        <!-- Submit -->
        <button type="submit"
            class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-3.5 rounded-xl text-sm transition-all duration-200 shadow-lg shadow-orange-500/30 active:scale-95 mt-2">
            Masuk ke Dashboard
        </button>

        <!-- Register Link -->
        @if (Route::has('register'))
            <p class="text-center text-sm text-gray-500 pt-2">
                Belum punya akun?
                <a href="{{ route('register') }}" wire:navigate
                    class="text-orange-500 font-semibold hover:text-orange-600 transition">
                    Daftar di sini
                </a>
            </p>
        @endif
    </form>
</div>

