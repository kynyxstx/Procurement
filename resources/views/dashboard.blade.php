<x-app-layout>
    <div x-data="{ show: false }" x-init="setTimeout(() => show = true, 100)" x-show="show"
        x-transition:enter="transition-opacity duration-700" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('PROCUREMENT MANAGEMENT SECTION') }}
            </h2>
        </x-slot>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8">
                    <div class="text-center">
                        @if(Auth::check())
                            <h1 class="text-3xl font-bold text-gray-800 mb-4">
                                Welcome, <a href="{{ route('profile.show') }}" class="text-black-600 hover:underline">
                                    {{ Auth::user()->name }}!
                                </a>
                            </h1>
                        @else
                            <h1 class="text-3xl font-bold text-gray-800 mb-4">
                                Welcome!
                            </h1>
                        @endif
                        <div class="flex flex-col md:flex-row gap-8 mb-8">
                            <div class="flex-1 p-6 bg-gray-100 rounded-lg shadow">
                                <h2 class="text-xl font-semibold text-blue-700 mb-2">Vision</h2>
                                <p class="text-gray-700 italic">
                                    Solid, responsive, and world-class authority on quality statistics, efficient civil
                                    registration,
                                    and inclusive identification system.
                                </p>
                            </div>
                            <div class="flex-1 p-6 bg-gray-100 rounded-lg shadow">
                                <h2 class="text-xl font-semibold text-blue-700 mb-2">Mission</h2>
                                <p class="text-gray-700 italic">
                                    Deliver relevant and reliable statistics, efficient civil registration services, and
                                    inclusive identification system for equitable development towards improved quality
                                    of
                                    life
                                    for all.
                                </p>
                            </div>
                        </div>
                        <div class="mb-8">
                            <div class="bg-gray-200 rounded-lg shadow-inner p-6">
                                <h2 class="text-2xl font-semibold text-blue-700 mb-4 text-center">CORE VALUES</h2>
                                <div class="flex flex-col md:flex-row gap-8">
                                    <div class="flex-1 p-6 bg-white rounded-lg shadow">
                                        <h3 class="text-xl font-semibold text-blue-700 mb-2">Integrity</h3>
                                        <p class="text-gray-700 text-justify">
                                            We observe the highest standards of professional behavior by exemplifying
                                            impartiality and independence in all our actions. We stand firm against
                                            undue
                                            influence, ensuring integrity is reflected not only in the statistics we
                                            deliver, but also in our people.
                                        </p>
                                    </div>
                                    <div class="flex-1 p-6 bg-white rounded-lg shadow">
                                        <h3 class="text-xl font-semibold text-blue-700 mb-2">Transparency</h3>
                                        <p class="text-gray-700 text-justify">
                                            We uphold transparency in all interactions and transactions to foster trust
                                            within and outside the PSA. We strive for clear communication, shared
                                            knowledge,
                                            and informed, inclusive decisions to cultivate mutual respect at every level
                                            of
                                            the organization.
                                        </p>
                                    </div>
                                    <div class="flex-1 p-6 bg-white rounded-lg shadow">
                                        <h3 class="text-xl font-semibold text-blue-700 mb-2">Adaptability</h3>
                                        <p class="text-gray-700 text-justify">
                                            We respond to change with a positive attitude and a willingness to learn new
                                            ways to fulfill our mandate. We embrace technological advancements and view
                                            challenges as opportunities to discover and enhance our services to the
                                            public.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-8 p-6 bg-gray-100 rounded-lg shadow">
                            <h2 class="text-xl font-semibold text-blue-700 mb-2">Quality Policy</h2>
                            <p class="text-gray-700 text-justify">
                                We, the Philippine Statistics Authority, commit to deliver relevant and reliable
                                statistics,
                                efficient civil registration services, and inclusive identification system to our
                                clients
                                and stakeholders.<br><br>
                                We adhere to the UN Fundamental Principles of Official Statistics in the production of
                                quality general-purpose statistics.<br><br>
                                We commit to deliver efficient civil registration services and inclusive identification
                                system in accordance with the laws, rules, and regulations, and other statutory
                                requirements.<br><br>
                                We endeavor to live by the established core values of the PSA and adapt the appropriate
                                technology in the development of our products and delivery of services to ensure
                                customer
                                satisfaction.<br><br>
                                We commit to continually improve the effectiveness of our Quality Management System
                                towards
                                equitable development for improved quality of life for all.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-8">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-4">Contact for Procurement</h2>
                    <ul class="list-disc list-inside mt-2">
                        <li class="mb-4">
                            <strong>Email:</strong>
                            <ul class="list-disc list-inside ml-6">
                                <p>
                                    <a href="mailto:gsdprocurement.psa@gmail.com" class="text-blue-600 hover:underline">
                                        gsdprocurement.psa@gmail.com
                                    </a>
                                </p>
                                <p>
                                    <a href="mailto:gsd.staff@psa.gov.ph" class="text-blue-600 hover:underline">
                                        gsd.staff@psa.gov.ph
                                    </a>
                                </p>
                            </ul>
                        </li>
                        <li>
                            <strong>Telephone Numbers:</strong>
                            <ul class="list-disc list-inside ml-6">
                                <p>(02) 8374-8262</p>
                                <p>(02) 8374-8263</p>
                                <p>(02) 8374-8270</p>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Back to Top Button -->
        <button id="backToTopBtn"
            class="fixed bottom-24 right-8 z-50 bg-blue-600 text-white px-4 py-2 rounded-full shadow-lg hover:bg-blue-800 transition-opacity opacity-0 pointer-events-none"
            style="transition: opacity 0.3s;" onclick="window.scrollTo({top: 0, behavior: 'smooth'});">
            ↑
        </button>
        <script>
            // Show/hide Back to Top button on scroll
            window.addEventListener('scroll', function () {
                const btn = document.getElementById('backToTopBtn');
                if (window.scrollY > 200) {
                    btn.style.opacity = '1';
                    btn.style.pointerEvents = 'auto';
                } else {
                    btn.style.opacity = '0';
                    btn.style.pointerEvents = 'none';
                }
            });
        </script>

        <!-- Tap to Down Button -->
        <button id="tapToDownBtn"
            class="fixed bottom-8 right-8 z-50 bg-blue-600 text-white px-4 py-2 rounded-full shadow-lg hover:bg-blue-800 transition-opacity opacity-0 pointer-events-none"
            style="transition: opacity 0.3s;"
            onclick="window.scrollTo({top: document.body.scrollHeight, behavior: 'smooth'});">
            ↓
        </button>
        <script>
            // Show/hide Tap to Down button on scroll (show when not at bottom)
            window.addEventListener('scroll', function () {
                const btn = document.getElementById('tapToDownBtn');
                if (window.innerHeight + window.scrollY < document.body.offsetHeight - 200) {
                    btn.style.opacity = '1';
                    btn.style.pointerEvents = 'auto';
                } else {
                    btn.style.opacity = '0';
                    btn.style.pointerEvents = 'none';
                }
            });
        </script>

        <footer class="mt-4 border-t pt-4 text-center text-gray-600 text-sm" style="min-height: 0.7in;">
            <span class="font-semibold">Procurement Management Section 2025</span>
        </footer>
</x-app-layout>