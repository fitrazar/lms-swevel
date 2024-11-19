<footer class="footer border-t border-base-200 text-base-content p-10">
    <aside>
        <div class="avatar">
            <div class="w-24 rounded">
                <img src="{{ asset('storage/' . $appSetting->logo) }}" alt="{{ $appSetting->alias }}">
            </div>
        </div>

        <p>
            {{ $appSetting->name }}
            <br />
            {{ $appSetting->motto }}
        </p>
    </aside>
    <nav>
        <h6 class="footer-title">Layanan</h6>
        <a class="link link-hover"
            href="https://api.whatsapp.com/send?phone={{ $appSetting->phone }}&text=Halo saya ingin bertanya mengenai jasa Pembuatan Website / Aplikasi"
            target="_blank">Pembuatan Website /
            Aplikasi</a>
        <a class="link link-hover"
            href="https://api.whatsapp.com/send?phone={{ $appSetting->phone }}&text=Halo saya ingin bertanya mengenai jasa Pembuatan Bot"
            target="_blank">Pembuatan BOT (WhatsApp
            /
            Telegram)</a>
        <a class="link link-hover"
            href="https://api.whatsapp.com/send?phone={{ $appSetting->phone }}&text=Halo saya ingin bertanya mengenai jasa Design UI/UX"
            target="_blank">Design UI/UX</a>
        <a class="link link-hover"
            href="https://api.whatsapp.com/send?phone={{ $appSetting->phone }}&text=Halo saya ingin bertanya mengenai jasa Digital Marketing"
            target="_blank">Digital Marketing</a>
        <a class="link link-hover"
            href="https://api.whatsapp.com/send?phone={{ $appSetting->phone }}&text=Halo saya ingin bertanya mengenai jasa SEO Specialist"
            target="_blank">SEO Specialist</a>
    </nav>
    <nav>
        <h6 class="footer-title">Media Sosial</h6>
        <a class="link link-hover" href="{{ $appSetting->instagram }}" target="_blank">Instagram</a>
        <a class="link link-hover" href="{{ $appSetting->facebook }}" target="_blank">Facebook</a>
    </nav>
    <nav>
        <h6 class="footer-title">Kontak</h6>
        <a class="link link-hover" href="https://wa.me/{{ $appSetting->phone }}" target="_blank">WhatsApp</a>
        <a class="link link-hover">{{ $appSetting->address }}</a>
    </nav>
</footer>
