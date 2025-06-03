<x-app-layout>
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
                            Welcome, {{ Auth::user()->name }}!
                        </h1>
                    @else
                        <h1 class="text-3xl font-bold text-gray-800 mb-4">
                            Welcome!
                        </h1>
                    @endif
                    <div class="mb-8 p-6 bg-gray-100 rounded-lg shadow">
                        <h2 class="text-xl font-semibold text-blue-700 mb-2">Vision</h2>
                        <p class="text-gray-700 italic">
                            Solid, responsive, and world-class authority on quality statistics, efficient civil
                            registration,
                            and inclusive identification system.
                        </p>
                    </div>
                    <div class="mb-8 p-6 bg-gray-100 rounded-lg shadow">
                        <h2 class="text-xl font-semibold text-blue-700 mb-2">Mission</h2>
                        <p class="text-gray-700 italic">
                            Deliver relevant and reliable statistics, efficient civil registration services, and
                            inclusive identification system for equitable development towards improved quality of life
                            for all.
                        </p>
                    </div>
                    <div class="mb-8 p-6 bg-gray-100 rounded-lg shadow">
                        <h2 class="text-xl font-semibold text-blue-700 mb-2">Quality Policy</h2>
                        <p class="text-gray-700 text-justify">
                            We, the Philippine Statistics Authority, commit to deliver relevant and reliable statistics,
                            efficient civil registration services, and inclusive identification system to our clients
                            and stakeholders.<br><br>
                            We adhere to the UN Fundamental Principles of Official Statistics in the production of
                            quality general-purpose statistics.<br><br>
                            We commit to deliver efficient civil registration services and inclusive identification
                            system in accordance with the laws, rules, and regulations, and other statutory
                            requirements.<br><br>
                            We endeavor to live by the established core values of the PSA and adapt the appropriate
                            technology in the development of our products and delivery of services to ensure customer
                            satisfaction.<br><br>
                            We commit to continually improve the effectiveness of our Quality Management System towards
                            equitable development for improved quality of life for all.
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <footer class="mt-12 border-t pt-6 text-center text-gray-600 text-sm">
            <span class="font-semibold">Procurement Management Section 2025</span>
        </footer>
    </div>
</x-app-layout>