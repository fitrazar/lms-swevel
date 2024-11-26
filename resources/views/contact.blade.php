@section('title', 'Kontak')

<x-guest-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="relative isolate hero rounded-lg p-4">
                <svg viewBox="0 0 1108 632" aria-hidden="true"
                    class="absolute top-10 -z-10 max-w-none transform-gpu blur-3xl lg:top-[calc(50%-30rem)]">
                    <path fill="url(#175c433f-44f6-4d59-93f0-c5c51ad5566d)" fill-opacity=".2"
                        d="M235.233 402.609 57.541 321.573.83 631.05l234.404-228.441 320.018 145.945c-65.036-115.261-134.286-322.756 109.01-230.655C968.382 433.026 1031 651.247 1092.23 459.36c48.98-153.51-34.51-321.107-82.37-385.717L810.952 324.222 648.261.088 235.233 402.609Z">
                    </path>
                    <defs>
                        <linearGradient id="175c433f-44f6-4d59-93f0-c5c51ad5566d" x1="1220.59" x2="-85.053"
                            y1="432.766" y2="638.714" gradientUnits="userSpaceOnUse">
                            <stop stop-color="#4F46E5"></stop>
                            <stop offset="1" stop-color="#80CAFF"></stop>
                        </linearGradient>
                    </defs>
                </svg>

                <div class="hero-content flex-col lg:flex-row-reverse">
                    {{-- <img src="{{ asset('assets/images/contact-illustration.png') }}" class="max-w-sm rounded-lg" /> --}}
                    <div>
                        <h1 class="text-5xl font-extrabold">Kontak Kami</h1>
                        <p class="py-6">
                            Kami siap membantu Anda dengan layanan yang Anda butuhkan. Silakan hubungi kami untuk
                            informasi lebih lanjut.
                        </p>
                    </div>
                </div>
            </div>

            <section id="about" class="py-5">
                <x-card.card-default class="static md:col-span-2 lg:col-span-4 col-span-1">
                    <div class="container">
                        <h2 class="text-center mb-4">Lokasi Kami</h2>
                        <div class="row d-flex">
                            <div class="col-md-12">
                                <iframe
                                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3981.969067461469!2d98.6718324757091!3d3.5945659502516536!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x303131c416b88825%3A0xc035ba4fa455d423!2sDeliPark%20Mall!5e0!3m2!1sid!2sid!4v1732607065385!5m2!1sid!2sid"
                                    width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                                    referrerpolicy="no-referrer-when-downgrade">
                                </iframe>
                            </div>
                        </div>
                    </div>
                </x-card.card-default>
            </section>

            <div class="py-12">
                <h2 class="font-bold text-center md:text-3xl text-lg">Informasi Kontak</h2>

                <div class="grid md:grid-cols-2 lg:grid-cols-4 grid-cols-1 gap-6 p-4">
                    <x-card.card-default class="static md:col-span-2 lg:col-span-4 col-span-1">
                        <div class="flex flex-col items-center space-y-4">
                            <div class="text-center">
                                <i class="fa-solid fa-envelope text-4xl"></i>
                                <h3 class="font-bold text-lg mt-2">Email</h3>
                                <p><a
                                        href="mailto:{{ $appSetting->email ?? '-' }}">{{ $appSetting->email ?? 'author@gmail.com' }}</a>
                                </p>
                            </div>

                            <div class="text-center">
                                <i class="fa-solid fa-phone-alt text-4xl"></i>
                                <h3 class="font-bold text-lg mt-2">Telepon</h3>
                                <p><a href="tel:+{{ $appSetting->phone }}">+{{ $appSetting->phone }}</a></p>
                            </div>

                            <div class="text-center">
                                <i class="fa-solid fa-map-marker-alt text-4xl"></i>
                                <h3 class="font-bold text-lg mt-2">Alamat</h3>
                                <p>{{ $appSetting->address }}</p>
                            </div>
                        </div>
                    </x-card.card-default>
                </div>
            </div>

        </div>
    </div>
</x-guest-layout>
