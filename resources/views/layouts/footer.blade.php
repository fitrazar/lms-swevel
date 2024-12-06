<footer class="footer border-t border-base-200 text-base-content p-10">
    <aside>
        <div class="avatar">
            <div class="w-24 rounded">
                <img src="{{ asset('storage/' . $appSetting?->logo) }}" alt="{{ $appSetting?->alias }}">
            </div>
        </div>

        <p>
            {{ $appSetting?->name ?? 'TIM 9' }}
            <br />
            {{ $appSetting->description ?? 'lorem ipsum' }}
        </p>
    </aside>
    <nav>
        <h6 class="footer-title">Layanan</h6>

    </nav>
    <nav>
        <h6 class="footer-title">Media Sosial</h6>
        @if ($appSetting?->social_media)
            @foreach (json_decode($appSetting->social_media, true) as $socialMedia)
                <a class="link link-hover" href="{{ $socialMedia['link'] }}"
                    target="_blank">{{ $socialMedia['platform'] }}</a>
            @endforeach
        @else
            <a class="link link-hover" href="#" target="_blank">Instagram</a>
            <a class="link link-hover" href="#" target="_blank">Facebook</a>
        @endif
    </nav>
    <nav>
        <h6 class="footer-title">Kontak</h6>
        <a class="link link-hover" href="https://wa.me/{{ $appSetting->phone ?? '#' }}" target="_blank">WhatsApp</a>
        <a class="link link-hover">{{ $appSetting->address }}</a>
    </nav>
</footer>
