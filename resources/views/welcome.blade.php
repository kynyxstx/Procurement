<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ asset('Images/favicon.ico') }}" type="image/x-icon">

    <title>Procurement</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <style>
        body {
            background-image: url('{{ asset('Images/1_bgwelcome.png') }}');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center center;
        }
    </style>


    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <style>
            body {
                background-image: url('{{ asset('Images/1_bgwelcome.png') }}');
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;
                background-attachment: fixed;
                /* Keeps the background fixed when scrolling */
                min-height: 100vh;
                /* Ensures body takes full viewport height */
                margin: 0;
                padding: 0;
                display: flex;
                flex-direction: column;
                align-items: center;
                /* Center items horizontally */
                justify-content: flex-start;
                /* Align content to the top initially */
                color: #1b1b18;
                /* Default text color, adjust if needed for contrast */
                font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
                /* Ensure font is applied */
            }

            /* Overlay for readability on top of the background image */
            body::before {
                content: "";
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(255, 255, 255, 0.6);
                /* White overlay with 60% opacity */
                /* Adjust opacity (0.0-1.0) for desired brightness/darkness */
                z-index: -1;
                /* Puts the overlay behind the content but in front of the background image */
            }

            /* Specific styles for the header (login/register buttons) */
            header {
                width: 100%;
                max-width: 1200px;
                /* Adjust max-width as needed */
                padding: 1rem;
                margin-bottom: 2rem;
                /* Space below header */
                display: flex;
                justify-content: flex-end;
                /* Align buttons to the right */
            }

            nav a {
                /* Your inline styles are more specific, so they will override these if present. */
                /* These are general styles for better visibility: */
                background: rgba(255, 255, 255, 0.92);
                /* Matches your inline style */
                backdrop-filter: blur(10px);
                /* Matches your inline style */
                color: #1b1b18;
                /* Ensure text is visible */
                border-color: rgba(25, 20, 0, 0.4);
                /* Make border slightly more visible */
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
                /* Slightly more prominent shadow */
                transition: all 0.2s ease-in-out;
                /* Smooth transition */
            }

            nav a:hover {
                background: rgba(255, 255, 255, 0.98);
                /* Less transparent on hover */
                border-color: rgba(25, 20, 0, 0.6);
            }

            /* Container for Vision, Mission, Quality Policy */
            .welcome-container {
                background-color: rgba(255, 255, 255, 0.9);
                /* Opaque white background for readability */
                border-radius: 15px;
                /* Slightly more rounded corners */
                padding: 3rem;
                /* More padding inside */
                max-width: 900px;
                /* Adjust max-width as needed */
                width: 100%;
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
                /* More pronounced shadow */
                margin-top: auto;
                /* Push container down if header is small */
                margin-bottom: auto;
                /* Center vertically if space allows */
            }

            .welcome-container h2 {
                text-align: center;
                margin-bottom: 1.5rem;
                /* Space below titles */
                font-size: 2rem;
                /* Larger font for titles */
                color: #333;
                /* Darker color for titles */
                text-transform: uppercase;
            }

            .vision-mission-container {
                display: flex;
                flex-direction: column;
                /* Stack columns on smaller screens */
                gap: 2.5rem;
                /* Space between columns */
                margin-bottom: 2.5rem;
            }

            @media (min-width: 768px) {

                /* Apply 2-column layout on medium screens and up */
                .vision-mission-container {
                    flex-direction: row;
                    /* Two columns side-by-side */
                }
            }

            .vision,
            .mission {
                flex: 1;
                /* Each takes equal space */
                text-align: justify;
                line-height: 1.8;
                /* Improve readability */
                font-size: 1.1rem;
                /* Slightly larger text */
                color: #555;
            }

            .quality-policy-title {
                margin-top: 2.5rem;
                /* Space above quality policy title */
            }

            .quality-policy-content p {
                text-align: justify;
                line-height: 1.8;
                font-size: 1.1rem;
                color: #555;
                margin-bottom: 1rem;
                /* Space between paragraphs */
            }
        </style>
    @endif
</head>

<body
    class="bg-[#FDFDFC] text-[#1b1b18] flex p-6 lg:p-20 items-center lg:justify-center min-h-screen flex-col font-sans">
    <header class="w-full lg:max-w-4xl max-w-[335px] text-sm mb-6 not-has-[nav]:hidden">
        @if (Route::has('login'))
            <nav class="flex items-center justify-end gap-4">
                @auth
                    <a href="{{ url('/dashboard') }}"
                        class="inline-block px-6 py-2 border border-indigo-700 text-white bg-indigo-700 rounded-sm text-base font-medium shadow-[0_2px_8px_0_rgba(0,0,0,0.06)] transition-all"
                        style="backdrop-filter: blur(10px);"
                        onmouseover="this.style.background='#4338ca';this.style.borderColor='#3730a3';"
                        onmouseout="this.style.background='#4f46e5';this.style.borderColor='#4f46e5';">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}"
                        class="inline-block px-6 py-2 text-white border border-indigo-700 bg-indigo-700 rounded-sm text-base font-medium shadow-[0_2px_8px_0_rgba(0,0,0,0.06)] transition-all"
                        style="backdrop-filter: blur(10px);"
                        onmouseover="this.style.background='#4338ca';this.style.borderColor='#3730a3';"
                        onmouseout="this.style.background='#4f46e5';this.style.borderColor='#4f46e5';">
                        Log In
                    </a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                            class="inline-block px-6 py-2 border border-indigo-700 text-white bg-indigo-700 rounded-sm text-base font-medium shadow-[0_2px_8px_0_rgba(0,0,0,0.06)] transition-all"
                            style="backdrop-filter: blur(10px);"
                            onmouseover="this.style.background='#4338ca';this.style.borderColor='#3730a3';"
                            onmouseout="this.style.background='#4f46e5';this.style.borderColor='#4f46e5';">
                            Register
                        </a>
                    @endif
                @endauth
            </nav>
        @endif
    </header>
    <div
        class="flex items-center justify-center w-full transition-opacity opacity-100 duration-750 lg:grow starting:opacity-0">
        <main class="flex max-w-[335px] w-full flex-col-reverse lg:max-w-4xl lg:flex-row">
            <!-- Add formal content here if needed -->
        </main>
    </div>

    @if (Route::has('login'))
        <div class="h-14.5 hidden lg:block"></div>
    @endif
</body>

</html>