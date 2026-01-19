<x-guest-layout>
    <div class="h-screen bg-gray-200 flex items-center justify-center overflow-hidden">
        <div class="w-full max-w-6xl h-full md:h-[90vh] bg-white rounded-2xl shadow-xl overflow-hidden grid grid-cols-1 md:grid-cols-2">

            <!-- LEFT PANEL -->
            <div class="relative">
                <img
                    src="{{ asset('images/auth/signup.jpg') }}"
                    class="absolute inset-0 w-full h-full object-cover"
                    alt="signup"
                >
                <!-- Dark overlay -->
                <div class="absolute inset-0 bg-black/55"></div>

                <!-- CENTERED CONTENT -->
                <div class="relative h-full w-full flex flex-col items-center justify-center text-center text-white px-12">
                    <h2 class="text-4xl md:text-5xl font-extrabold mb-4">
                        Welcome<br>back
                    </h2>

                    <p class="text-sm text-white/80 max-w-sm mb-8">
                        To keep connected with us provide us with your information
                    </p>

                    <a href="{{ route('login') }}"
                       class="inline-flex items-center justify-center w-32 h-11 rounded-md bg-white/25 text-white text-sm font-semibold hover:bg-white/35 transition">
                        Signin
                    </a>
                </div>
            </div>

            <!-- RIGHT PANEL -->
            <div class="bg-gray-50 flex items-center">
                <div class="w-full px-10 md:px-20">

                    <div class="text-center">
                        <div class="text-sm font-semibold">
                            Event <span class="text-indigo-600">Planner</span>
                        </div>
                        <h1 class="text-3xl md:text-4xl font-extrabold mt-3">
                            Sign Up to Event Planner
                        </h1>
                    </div>

                    <form method="POST" action="{{ route('register') }}" class="mt-10 space-y-5">
                        @csrf

                        <!-- NAME -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-2">YOUR NAME</label>
                            <input
                                name="name"
                                type="text"
                                value="{{ old('name') }}"
                               
                                autofocus
                                placeholder="Enter your name"
                                class="w-full h-11 rounded-md border border-gray-200 bg-white px-3 text-sm
                                       focus:border-indigo-500 focus:ring-indigo-500"
                            >
                            <!-- ERROR -->
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- EMAIL -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-2">YOUR EMAIL</label>
                            <input
                                name="email"
                                type="email"
                                value="{{ old('email') }}"
                                
                                placeholder="Enter your email"
                                class="w-full h-11 rounded-md border border-gray-200 bg-white px-3 text-sm
                                       focus:border-indigo-500 focus:ring-indigo-500"
                            >
                            <!-- ERROR -->
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- PASSWORD -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-2">PASSWORD</label>
                            <input
                                name="password"
                                type="password"
                                
                                placeholder="Enter your password"
                                class="w-full h-11 rounded-md border border-gray-200 bg-white px-3 text-sm
                                       focus:border-indigo-500 focus:ring-indigo-500"
                            >
                            <!-- ERROR -->
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <!-- CONFIRM PASSWORD -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-2">CONFIRM PASSWORD</label>
                            <input
                                name="password_confirmation"
                                type="password"
                                
                                placeholder="Enter your password"
                                class="w-full h-11 rounded-md border border-gray-200 bg-white px-3 text-sm
                                       focus:border-indigo-500 focus:ring-indigo-500"
                            >
                            <!-- ERROR -->
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>

                        <button type="submit"
                                class="w-full h-12 rounded-md bg-indigo-600 text-white text-sm font-semibold
                                       hover:bg-indigo-700 transition">
                            Sign Up
                        </button>
                    </form>

                    <!-- Mobile link -->
                    <div class="md:hidden mt-8 text-center text-sm text-gray-600">
                        Already have an account?
                        <a href="{{ route('login') }}" class="text-indigo-600 font-semibold">Signin</a>
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-guest-layout>
