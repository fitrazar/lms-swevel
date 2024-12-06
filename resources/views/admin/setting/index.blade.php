@section('title', 'Pengaturan Website')

<x-app-layout>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-card.card-default class="static">
                @if (session()->has('success'))
                    <x-alert.success :message="session('success')" />
                @endif

                <x-form action="{{ route('dashboard.admin.setting.store') }}" class="md:grid md:grid-cols-2 gap-4"
                    enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" name="oldImage" value="{{ $setting?->logo }}">
                    <div class="mt-4">
                        @if ($setting?->logo)
                            <div class="avatar">
                                <div class="w-32 rounded-xl">
                                    <img src="{{ asset('storage/' . $setting?->logo) }}" />
                                </div>
                            </div>
                        @endif
                        <img class="imgPreview h-auto max-w-lg mx-auto hidden" alt="logo">
                        <x-input.input-label for="logo" :value="__('Logo')" />
                        <x-input.input-file id="logo" class="mt-1 w-full" type="file" name="logo"
                            :value="old('logo', $setting?->logo)" autofocus autocomplete="logo" onchange="previewImage()" />
                        <x-input.input-error :messages="$errors->get('logo')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input.input-label for="name" :value="__('Nama')" />
                        <x-input.text-input id="name" class="mt-1 w-full" type="text" name="name"
                            :value="old('name', $setting?->name ?? '')" required autofocus autocomplete="name" />
                        <x-input.input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>
                    <div class="mt-4">
                        <x-input.input-label for="alias" :value="__('Singkatan')" />
                        <x-input.text-input id="alias" class="mt-1 w-full" type="text" name="alias"
                            :value="old('alias', $setting?->alias ?? '')" required autofocus autocomplete="alias" />
                        <x-input.input-error :messages="$errors->get('alias')" class="mt-2" />
                    </div>
                    <div class="mt-4">
                        <x-input.input-label for="description" :value="__('Deskripsi')" />
                        <x-input.text-area id="description" class="mt-1 w-full" type="text" name="description"
                            :value="old('description', $setting?->description ?? '')" required autofocus autocomplete="description" />
                        <x-input.input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>
                    <div class="mt-4">
                        <x-input.input-label for="address" :value="__('Alamat Lengkap')" />
                        <x-input.text-area id="address" class="mt-1 w-full" type="text" name="address"
                            :value="old('address', $setting?->address ?? '')" required autofocus autocomplete="address" />
                        <x-input.input-error :messages="$errors->get('address')" class="mt-2" />
                    </div>
                    <div class="mt-4">
                        <x-input.input-label for="phone" :value="__('No Telpon')" />
                        <x-input.text-input id="phone" class="mt-1 w-full" type="number" name="phone"
                            :value="old('phone', $setting?->phone ?? '')" required autofocus autocomplete="phone" />
                        <x-input.input-error :messages="$errors->get('phone')" class="mt-2" />
                    </div>
                    <div class="mt-4">
                        <x-input.input-label for="email" :value="__('Email')" />
                        <x-input.text-input id="email" class="mt-1 w-full" type="text" name="email"
                            :value="old('email', $setting?->email ?? '')" required autofocus autocomplete="email" />
                        <x-input.input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>
                    <div class="mt-4">
                        <x-input.input-label for="map" :value="__('Peta')" />
                        <x-input.text-input id="map" class="mt-1 w-full" type="text" name="map"
                            :value="old('map', $setting?->map ?? '')" required autofocus autocomplete="map" />
                        <x-input.input-error :messages="$errors->get('map')" class="mt-2" />
                    </div>
                    <div class="mt-4">
                        <x-input.input-label for="social_media" :value="__('Sosial Media')" />
                        <div id="socialMediaContainer">
                            @if (isset($setting?->social_media))
                                @foreach (json_decode($setting?->social_media, true) as $index => $socialMedia)
                                    <div class="social-media-item flex items-center gap-4 mt-2">
                                        <x-input.text-input name="social_media[{{ $index }}][platform]"
                                            class="w-1/3" placeholder="Platform (e.g., Facebook)"
                                            value="{{ $socialMedia['platform'] }}" />
                                        <x-input.text-input name="social_media[{{ $index }}][link]"
                                            class="w-2/3" placeholder="URL (e.g., https://facebook.com)"
                                            value="{{ $socialMedia['link'] }}" />
                                        <button type="button"
                                            class="removeSocialMedia btn btn-error btn-sm">Hapus</button>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <button type="button" id="addSocialMedia" class="btn btn-primary btn-sm mt-2">
                            Tambah Sosial Media
                        </button>
                    </div>



                    <div class="col-span-2">
                        <x-button.primary-button type="submit">
                            {{ __('Simpan') }}
                        </x-button.primary-button>
                    </div>

                </x-form>
            </x-card.card-default>
        </div>
    </div>
    <x-slot name="script">
        <script>
            function previewImage() {
                const image = document.querySelector('#logo')
                const imgPreview = document.querySelector('.imgPreview')

                imgPreview.style.display = 'block'

                const oFReader = new FileReader()
                oFReader.readAsDataURL(image.files[0])
                oFReader.onload = function(oFREvent) {
                    imgPreview.src = oFREvent.target.result
                }
            }

            document.addEventListener("DOMContentLoaded", function() {
                const socialMediaContainer = document.getElementById("socialMediaContainer");
                const addSocialMediaBtn = document.getElementById("addSocialMedia");

                let socialMediaIndex = socialMediaContainer.children.length;

                addSocialMediaBtn.addEventListener("click", () => {
                    const newSocialMedia = document.createElement("div");
                    newSocialMedia.classList.add("social-media-item", "flex", "items-center", "gap-4", "mt-2");
                    newSocialMedia.innerHTML = `
                <x-input.text-input
                    name="social_media[${socialMediaIndex}][platform]"
                    class="w-1/3"
                    placeholder="Platform (e.g., Facebook)"
                />
                <x-input.text-input
                    name="social_media[${socialMediaIndex}][link]"
                    class="w-2/3"
                    placeholder="URL (e.g., https://facebook.com)"
                />
                <button type="button" class="removeSocialMedia btn btn-error btn-sm">Hapus</button>
            `;

                    socialMediaContainer.appendChild(newSocialMedia);
                    socialMediaIndex++;
                });

                socialMediaContainer.addEventListener("click", (event) => {
                    if (event.target.classList.contains("removeSocialMedia")) {
                        event.target.parentElement.remove();
                    }
                });
            });
        </script>
    </x-slot>
</x-app-layout>
