<x-guest-layout>
    <div class="h-screen bg-gray-200 flex items-center justify-center overflow-hidden px-4">
        <div class="w-full max-w-6xl h-full md:h-[90vh] bg-white rounded-2xl shadow-xl overflow-hidden grid grid-cols-1 md:grid-cols-2">

            <!-- LEFT IMAGE PANEL -->
            <div class="relative">
                <img
                    src="{{ asset('images/auth/signin.jpg') }}"
                    class="absolute inset-0 w-full h-full object-cover"
                    alt="signin"
                >
                <!-- Dark overlay -->
                <div class="absolute inset-0 bg-black/55"></div>

                <!-- CENTERED CONTENT -->
                <div class="relative h-full w-full flex flex-col items-center justify-center text-center text-white px-12">
                    <h2 class="text-4xl md:text-5xl font-extrabold mb-4">
                        Hello Friend
                    </h2>

                    <p class="text-sm text-white/80 max-w-sm mb-8">
                        To keep connected with us provide us with your information
                    </p>

                    <a href="{{ route('register') }}"
                       class="inline-flex items-center justify-center w-32 h-11 rounded-md bg-white/25 text-white text-sm font-semibold hover:bg-white/35 transition">
                        Signup
                    </a>
                </div>
            </div>

            <!-- RIGHT FORM PANEL -->
            <div class="bg-gray-50 flex items-center">
                <div class="w-full px-10 md:px-20">

                    <div class="text-center">
                        <div class="text-sm font-semibold">
                            Event <span class="text-indigo-600">Planner</span>
                        </div>
                        <h1 class="text-3xl md:text-4xl font-extrabold mt-3">
                            Sign In to Event Planner
                        </h1>
                    </div>

                    <x-auth-session-status class="mt-4" :status="session('status')" />

                    <form method="POST" action="{{ route('login') }}" class="mt-10 space-y-6">
                        @csrf

                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-2">YOUR EMAIL</label>
                            <input
                                name="email"
                                type="email"
                                value="{{ old('email') }}"
                                required
                                autofocus
                                placeholder="Enter your mail"
                                class="w-full h-11 rounded-md border border-gray-200 bg-white px-3 text-sm
                                       focus:border-indigo-500 focus:ring-indigo-500"
                            >
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label class="block text-xs font-semibold text-gray-500">
                                    PASSWORD
                                </label>

                                @if (Route::has('password.request'))
                                    <a class="text-xs text-gray-400 hover:text-indigo-600"
                                       href="{{ route('password.request') }}">
                                        Forgot your password?
                                    </a>
                                @endif
                            </div>

                            <input
                                name="password"
                                type="password"
                                required
                                placeholder="Enter your password"
                                class="w-full h-11 rounded-md border border-gray-200 bg-white px-3 text-sm
                                       focus:border-indigo-500 focus:ring-indigo-500"
                            >
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <button type="submit"
                                class="w-full h-12 rounded-md bg-indigo-600 text-white text-sm font-semibold
                                       hover:bg-indigo-700 transition">
                            Sign In
                        </button>
                    </form>

                    <!-- Mobile link -->
                    <div class="md:hidden mt-8 text-center text-sm text-gray-600">
                        Don’t have an account?
                        <a href="{{ route('register') }}" class="text-indigo-600 font-semibold">Signup</a>
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-guest-layout>
