<x-layout>
    <h1 style="display: flex; justify-content: center; height: 65px">Search Posts</h1>
    
        <form method="POST" action="/search" style="display: flex; justify-content: center;">
            @csrf
            <input type="text" name="search" placeholder="Search Posts">
            <button type="submit">Search</button>
        </form>
    
        @if (isset($posts))
        <h2>Search Results:</h2>
        <ul>
            @foreach ($posts as $post)
            <a href="/post/{{$post->id}}" class="list-group-item list-group-item-action">
                <img class="avatar-tiny" src="{{ $post->user->avatar }}" />
                <strong>{{$post->title}}</strong> by {{ $post->user->username }} on {{$post->created_at->format('n/j/Y')}}
            </a>
            @endforeach
        </ul>
        @else
        <p style="display: flex; justify-content: center;">No results found.</p>
        @endif
    </x-layout>