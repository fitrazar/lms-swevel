@section('title', 'Dashboard')

<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @hasrole('author')
                @include('partials.author')
            @endrole
            @hasrole('instructor')
                @include('partials.instructor')
            @endrole
            @hasrole('participant')
                @include('partials.participant')
            @endrole
        </div>
    </div>


    <x-slot name="script">
        @hasrole('participant')
            @include('partials.scripts.participant')
        @endrole
        @hasrole('instructor')
            @include('partials.scripts.instructor')
        @endrole
        @hasrole('author')
            @include('partials.scripts.author')
        @endrole
    </x-slot>
</x-app-layout>
