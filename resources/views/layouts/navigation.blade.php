<div class="navbar bg-base-100 shadow-sm z-10 fixed bg-transparent backdrop-blur-lg border-b border-base-200">

    <div class="navbar-start ml-10">
        <span class="font-extrabold">{{ $appSetting->name }}</span>
    </div>
    <div class="navbar-center hidden lg:flex">
        <ul class="menu menu-horizontal px-1 flex gap-4">
            <li><a href="{{ route('home') }}">Beranda</a></li>
            <li><a href="{{ route('course.index') }}">Kursus</a></li>
            <li><a href="{{ route('contact') }}">Kontak Kami</a></li>
            @auth
                @hasrole('author')
                    <li>
                        <details>
                            <summary>Master Data</summary>
                            <ul class="p-2 z-10">
                                <li><a href="{{ route('dashboard.admin.participant.index') }}">Data Peserta</a></li>
                                <li><a href="{{ route('dashboard.admin.instructor.index') }}">Data Mentor</a></li>
                                <li><a href="{{ route('dashboard.admin.course.index') }}">Data Kursus</a></li>
                                <li><a href="{{ route('dashboard.enrollment.index') }}">Data Pendaftaran</a></li>
                                <li><a href="{{ route('dashboard.material.index') }}">Data Materi</a></li>
                                <li><a href="{{ route('dashboard.assignment.index') }}">Data Tugas</a></li>
                                <li><a href="{{ route('dashboard.quiz.index') }}">Data Kuis</a></li>
                            </ul>
                        </details>
                    </li>
                    <li>
                        <details>
                            <summary>Kuis</summary>
                            <ul class="p-2 z-10">
                                <li><a href="{{ route('dashboard.question.index') }}">Data Pertanyaan</a></li>
                            </ul>
                        </details>
                    </li>
                    <li><a href="{{ route('dashboard.admin.setting.index') }}">Pengaturan</a></li>
                @endrole
                @hasanyrole('author|instructor')
                @endhasanyrole
                @hasrole('instructor')
                    <li>
                        <details>
                            <summary>Master Data</summary>
                            <ul class="p-2 z-10">
                                <li><a href="{{ route('dashboard.instructor.course.index') }}">Data Kursus</a></li>
                                <li><a href="{{ route('dashboard.enrollment.index') }}">Data Pendaftaran</a></li>
                                <li><a href="{{ route('dashboard.material.index') }}">Data Materi</a></li>
                                <li><a href="{{ route('dashboard.assignment.index') }}">Data Tugas</a></li>
                                <li><a href="{{ route('dashboard.quiz.index') }}">Data Kuis</a></li>
                            </ul>
                        </details>
                    </li>
                    <li>
                        <details>
                            <summary>Kuis</summary>
                            <ul class="p-2 z-10">
                                <li><a href="{{ route('dashboard.question.index') }}">Data Pertanyaan</a></li>
                                <li><a href="{{ route('dashboard.instructor.quiz.result') }}">Hasil Kuis</a></li>
                            </ul>
                        </details>
                    </li>
                @endrole
                @role('participant')
                    <li><a href="{{ route('dashboard.participant.quiz.index') }}">Kuis</a></li>
                @endrole
            @else
            @endauth

        </ul>
    </div>
    <div class="navbar-end mr-10">
        <div class="dropdown dropdown-end">
            @auth
                <div tabindex="0" role="button" class="btn btn-ghost btn-circle avatar">
                    @php
                        $words = explode(' ', Auth::user()->email);
                        $acronym = mb_substr($words[0] ?? 'I', 0, 1) . mb_substr($words[1] ?? 'T', 0, 1);
                    @endphp
                    <div class="avatar placeholder">
                        <div class="bg-neutral text-neutral-content rounded-full w-8">
                            <span class="text-xs">{{ $acronym }}</span>
                        </div>
                    </div>
                </div>
                <ul tabindex="0" class="menu menu-sm dropdown-content mt-3 z-[1] p-2 shadow bg-base-100 rounded-box w-52">
                    <li>
                        <a href="{{ route('dashboard.index') }}">
                            {{ __('Dashboard') }}
                        </a>
                    </li>
                    @hasrole('participant')
                        <li>
                            <a href="{{ route('dashboard.participant.quiz.index') }}">
                                {{ __('Kuis') }}
                            </a>
                        </li>
                    @endrole
                    @hasanyrole('participant|instructor')
                        <li>
                            <a href="{{ route('dashboard.profile.edit') }}">
                                {{ __('Profile') }}
                            </a>
                        </li>
                    @endhasanyrole
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <li>
                            <a href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                        this.closest('form').submit();">{{ __('Log Out') }}</a>
                        </li>
                    </form>
                </ul>
            @else
                <div tabindex="0" role="button" class="btn btn-ghost btn-circle avatar">
                    <div class="avatar placeholder">
                        <div class="bg-neutral text-neutral-content rounded-full w-8">
                            <i class="fa-regular fa-user"></i>
                        </div>
                    </div>
                </div>
                <ul tabindex="0" class="menu menu-sm dropdown-content mt-3 z-[1] p-2 shadow bg-base-100 rounded-box w-52">
                    <li>
                        <a href="{{ route('login') }}">
                            {{ __('Login') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('register') }}">
                            {{ __('Register') }}
                        </a>
                    </li>
                </ul>
            @endauth
        </div>

        <label class="swap swap-rotate ml-5">
            <input type="checkbox" class="theme-controller hidden" value="dark" />
            <svg class="swap-off fill-current w-10 h-10" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path
                    d="M5.64,17l-.71.71a1,1,0,0,0,0,1.41,1,1,0,0,0,1.41,0l.71-.71A1,1,0,0,0,5.64,17ZM5,12a1,1,0,0,0-1-1H3a1,1,0,0,0,0,2H4A1,1,0,0,0,5,12Zm7-7a1,1,0,0,0,1-1V3a1,1,0,0,0-2,0V4A1,1,0,0,0,12,5ZM5.64,7.05a1,1,0,0,0,.7.29,1,1,0,0,0,.71-.29,1,1,0,0,0,0-1.41l-.71-.71A1,1,0,0,0,4.93,6.34Zm12,.29a1,1,0,0,0,.7-.29l.71-.71a1,1,0,1,0-1.41-1.41L17,5.64a1,1,0,0,0,0,1.41A1,1,0,0,0,17.66,7.34ZM21,11H20a1,1,0,0,0,0,2h1a1,1,0,0,0,0-2Zm-9,8a1,1,0,0,0-1,1v1a1,1,0,0,0,2,0V20A1,1,0,0,0,12,19ZM18.36,17A1,1,0,0,0,17,18.36l.71.71a1,1,0,0,0,1.41,0,1,1,0,0,0,0-1.41ZM12,6.5A5.5,5.5,0,1,0,17.5,12,5.51,5.51,0,0,0,12,6.5Zm0,9A3.5,3.5,0,1,1,15.5,12,3.5,3.5,0,0,1,12,15.5Z" />
            </svg>
            <svg class="swap-on fill-current w-10 h-10" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path
                    d="M21.64,13a1,1,0,0,0-1.05-.14,8.05,8.05,0,0,1-3.37.73A8.15,8.15,0,0,1,9.08,5.49a8.59,8.59,0,0,1,.25-2A1,1,0,0,0,8,2.36,10.14,10.14,0,1,0,22,14.05,1,1,0,0,0,21.64,13Zm-9.5,6.69A8.14,8.14,0,0,1,7.08,5.22v.27A10.15,10.15,0,0,0,17.22,15.63a9.79,9.79,0,0,0,2.1-.22A8.11,8.11,0,0,1,12.14,19.73Z" />
            </svg>

        </label>

    </div>


</div>

<div class="btm-nav lg:hidden shadow-sm z-10">

    <a href="#" class="{{ Request::is('/') ? 'active' : '' }}">
        <i class="fa-solid fa-house" class="h-5 w-5"></i>
        <span class="btm-nav-label text-xs">Beranda</span>
    </a>
    <a href="#" class="{{ Request::is('/course') ? 'active' : '' }}">
        <i class="fa-solid fa-book" class="h-5 w-5"></i>
        <span class="btm-nav-label text-xs">Kursus</span>
    </a>

    <a href="#" class="{{ Request::is('/contact') ? 'active' : '' }}">
        <i class="fa-solid fa-address-book" class="h-5 w-5"></i>
        <span class="btm-nav-label text-xs">Kontak</span>
    </a>
</div>
