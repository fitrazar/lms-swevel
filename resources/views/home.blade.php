@section('title', 'Beranda')

<x-guest-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- batas --}}
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
                    <img src="{{ asset('assets/images/illustration.png') }}" class="max-w-sm rounded-lg" />
                    <div>
                        <h1 class="text-5xl font-extrabold">Kumpulan kursus digital, design sampai networking</h1>
                        <p class="py-6">
                            Temukan kursus teknologi yang kamu inginkan sekarang.
                        </p>
                        <button class="btn btn-primary"><i class="fa-solid fa-rocket"></i> Get Started</button>
                    </div>
                </div>
            </div>

            <div class="container mt-3">
                <hr>
                <h1 class="text-3xl font-extrabold text-center my-2">Latest Course</h1>
                <div class="grid grid-cols-1 lg:grid-cols-3">
                  @foreach ($latestCourse as $course)
                  <div class="card glass w-96  mx-auto my-5">
                    <figure>
                      <img
                      src="https://img.daisyui.com/images/stock/photo-1606107557195-0e29a4b5b4aa.webp"
                      alt="card!" />
                    </figure>
                    <div class="card-body">
                      <h2 class="card-title text-bold">{{$course->title}}</h2>
                      <p>{{$course->description}}</p>
                      <p><i class="fa-solid fa-calendar-days"></i> &nbsp;start date : {{$course->start_date}}</p>
                      <p><i class="fa-solid fa-book"></i>&nbsp;{{$course->duration}} topic</p>
                      <span class="badge badge-error gap-2">Technology</span>
                      <span class="badge badge-info gap-2">Laravel</span>
                      <div class="card-actions justify-end">
                        <button class="btn btn-primary">Learn now!</button>
                      </div>
                    </div>
                  </div>
                  @endforeach
                </div>
                <div class="flex justify-center mb-10 mt-5">
                <button class="btn btn-primary">
                  <i class="fa-solid fa-search mr-2"></i>Lihat Semua
                </button>
              </div>
            </div>  

            <div class="container px-auto">
              <hr>
              <div class="text-3xl font-extrabold text-center my-2">
                <h1>Our Teams</h1>
              </div>

              <div class="grid content-center lg:grid-flow-col sm:grid-flow-row gap-3 ">
                <div class="card bg-base-100 w-100 shadow-xl  mx-auto">
                  <div class="card-body items-center text-center">
                    <div class="avatar">
                      <div class="mask mask-squircle w-24">
                        <img src="https://img.daisyui.com/images/stock/photo-1534528741775-53994a69daeb.webp" />
                      </div>
                    </div>
                    <h2 class="card-title">Muhammad Fitra Fajar</h2>
                    <p>CEO | Project Lead</p>
                    <div class="card-actions">
                      <button class="btn btn-primary"><i class="fa-brands fa-facebook"></i></button>
                      <button class="btn btn-primary"><i class="fa-brands fa-instagram"></i></button>
                      <button class="btn btn-primary"><i class="fa-brands fa-linkedin"></i></button>
                    </div>
                  </div>
                </div>

                <div class="card bg-base-100 w-100 shadow-xl  mx-auto">
                  <div class="card-body items-center text-center">
                    <div class="avatar">
                      <div class="mask mask-squircle w-24">
                        <img src="https://img.daisyui.com/images/stock/photo-1534528741775-53994a69daeb.webp" />
                      </div>
                    </div>
                    <h2 class="card-title">Khairul Fanani</h2>
                    <p>Back End Dev</p>
                    <div class="card-actions">
                      <button class="btn btn-primary"><i class="fa-brands fa-facebook"></i></button>
                      <button class="btn btn-primary"><i class="fa-brands fa-instagram"></i></button>
                      <button class="btn btn-primary"><i class="fa-brands fa-linkedin"></i></button>
                    </div>
                  </div>
                </div>

                <div class="card bg-base-100 w-100 shadow-xl  mx-auto">
                  <div class="card-body items-center text-center">
                    <div class="avatar">
                      <div class="mask mask-squircle w-24">
                        <img src="https://img.daisyui.com/images/stock/photo-1534528741775-53994a69daeb.webp" />
                      </div>
                    </div>
                    <h2 class="card-title">Dheny Cahyono</h2>
                    <p>Support System</p>
                    <div class="card-actions">
                      <button class="btn btn-primary"><i class="fa-brands fa-facebook"></i></button>
                      <button class="btn btn-primary"><i class="fa-brands fa-instagram"></i></button>
                      <button class="btn btn-primary"><i class="fa-brands fa-linkedin"></i></button>
                    </div>
                  </div>
                </div>

                <div class="card bg-base-100 w-100 shadow-xl  mx-auto">
                  <div class="card-body items-center text-center">
                    <div class="avatar">
                      <div class="mask mask-squircle w-24">
                        <img src="https://img.daisyui.com/images/stock/photo-1534528741775-53994a69daeb.webp" />
                      </div>
                    </div>
                    <h2 class="card-title">Muhammad Renaldy Saputra</h2>
                    <p>Front End Dev</p>
                    <div class="card-actions">
                      <button class="btn btn-primary"><i class="fa-brands fa-facebook"></i></button>
                      <button class="btn btn-primary"><i class="fa-brands fa-instagram"></i></button>
                      <button class="btn btn-primary"><i class="fa-brands fa-linkedin"></i></button>
                    </div>
                  </div>
                </div>
                
                <div class="card bg-base-100 w-100 shadow-xl  mx-auto">
                  <div class="card-body items-center text-center">
                    <div class="avatar">
                      <div class="mask mask-squircle w-24">
                        <img src="https://img.daisyui.com/images/stock/photo-1534528741775-53994a69daeb.webp" />
                      </div>
                    </div>
                    <h2 class="card-title">EL DEROXVILON</h2>
                    <p>System Analyst</p>
                    <div class="card-actions">
                      <button class="btn btn-primary"><i class="fa-brands fa-facebook"></i></button>
                      <button class="btn btn-primary"><i class="fa-brands fa-instagram"></i></button>
                      <button class="btn btn-primary"><i class="fa-brands fa-linkedin"></i></button>
                    </div>
                  </div>
                </div>

              </div>    
            </div>
        
        {{--  --}}
        </div>
    </div>
</x-guest-layout>
