@section('title', 'Courses')

<x-guest-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="container mx-auto grid">

               
                <div class="hero bg-base-200 min-h-screen">
                    <div class="hero-content flex-col">
                      <img
                        src="https://img.daisyui.com/images/stock/photo-1635805737707-575885ab0820.webp"
                        class="max-w-sm rounded-lg shadow-2xl" />
                      <div>
                        <h1 class="text-5xl font-bold">{{$course->title}}</h1>
                        <p class="py-6">{{$course->description}}</p>
                        <p class="mt-3"><i class="fa-solid fa-calendar-days"></i>&nbsp;Start date : {{$course->start_date}}</p>
                        <p class=""><i class="fa-solid fa-calendar-days"></i>&nbsp;End date : {{$course->end_date}}</p>
                        <p>intructors &nbsp;
                            @foreach ($course->instructors as $intructor)
                            <span class="badge badge-primary">
                                {{$intructor->name}}
                            </span>
                            @endforeach
                        </p>
                       
                        <button class="mt-3 btn btn-primary">Full access</button>
                      </div>                     
                    </div>
                </div>
                
                <div class="card mt-6 grid-row bg-base-200 p-8">
                    <div class="card">
                        <h1>Overview</h1>
                    </div>
                    <details class="collapse">
                        <summary class="collapse-title text-xl font-medium ml-3 mt-3">Topic for this course</summary>
                        <div class="collapse-content border border-sky-500 p-8">
                          @forelse ($course->topics as $topic)
                              <h1>{{$topic->title}}</h1>
                          @empty
                              <h2>Belum ada topik yang ditambah</h2>
                          @endforelse
                        </div>
                      </details>
                </div>

{{-- div template --}}
            </div>
        </div>
    </div>
</x-guest-layout>