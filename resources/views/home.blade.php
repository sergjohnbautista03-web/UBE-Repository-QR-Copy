@extends('layouts.app')

@section('title', 'Ube Repository — Online Research Portal')

@section('content')
<div class="hero-banner">
    <div class="hero-bg-mesh"></div>
    <div class="hero-inner">
        <h1 class="hero-title">Ube Research Repository</h1>
        <p class="hero-sub">Discover academic researches</p>
    </div>
</div>

<div class="page-container">
    <aside class="sidebar">

        <!-- SEARCH BOX -->
        <form action="{{ route('home') }}" method="GET" class="box search-box">
            <h3 class="box-title">Search Research</h3>

            <div class="search-group">
                <label>Keyword</label>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Title, author, keyword..." autocomplete="off">
            </div>

            <div class="search-group">
                <label>Search By</label>
                <select name="field">
                    <option value="all" {{ request('field') == 'all' ? 'selected' : '' }}>All Fields</option>
                    <option value="title" {{ request('field') == 'title' ? 'selected' : '' }}>Title</option>
                    <option value="author" {{ request('field') == 'author' ? 'selected' : '' }}>Author</option>
                    <option value="abstract" {{ request('field') == 'abstract' ? 'selected' : '' }}>Abstract</option>
                </select>
            </div>

            <div class="search-group">
                <label>Department</label>
                <select name="department">
                <option value="">All Departments</option>
                 @foreach([
                    'College of Accountancy and Business Education',
                    'College of Computer Studies',
                    'College of Criminal Justice Education',
                    'College of Education',
                    'College of Engineering',
                    'College of Maritime Studies',
                ] as $dept)
                    <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                @endforeach
            </select>
            </div>

            <div class="search-group">
                <label>Year Range</label>
                <div class="year-range">
                    <input type="number" name="year_from" value="{{ request('year_from', 2021) }}" placeholder="From" min="2021" max="{{ date('Y') }}">
                    <span>—</span>
                    <input type="number" name="year_to" value="{{ request('year_to', date('Y')) }}" placeholder="To" min="2021" max="{{ date('Y') }}">
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-full">Search</button>
            @if(request()->anyFilled(['search','field','department','year_from','year_to']))
                <a href="{{ route('home') }}" class="btn btn-ghost btn-full" style="margin-top:8px">Clear Filters</a>
            @endif
        </form>

        <!-- BROWSE BOX -->
        <div class="box">
            <h3 class="box-title">Browse By</h3>
            <ul class="browse-list">
                @foreach([
                    'College of Accountancy and Business Education',
                    'College of Computer Studies',
                    'College of Criminal Justice Education',
                    'College of Education',
                    'College of Engineering',
                    'College of Maritime Studies',
                ] as $dept)
                <li>
                    <a href="{{ route('research.department', rawurlencode($dept)) }}"
                       class="{{ request()->is('department/*') && urldecode(request()->segment(2)) == $dept ? 'active' : '' }}">
                        {{ $dept }}
                    </a>
                </li>
                @endforeach
            </ul>
        </div>

        @auth
        @if(auth()->user()->isResearcher())
<div class="box">
    <h3 class="box-title">My Space</h3>
    <ul class="browse-list">
        <li><a href="{{ route('research.submit') }}">+ Submit Research</a></li>
        <li><a href="{{ route('research.my-submissions') }}">My Submissions</a></li>
        <li><a href="{{ route('research.pinned') }}">Pinned Papers</a></li>
    </ul>
</div>
        @endif
        @endauth

    </aside>

    <main class="content">
        @if(request()->anyFilled(['search','department','year_from','year_to']))
            <h2 class="section-title">
                Search Results
                <span class="result-count">{{ $researches->total() }} found</span>
            </h2>

            @if($researches->isEmpty())
                <div class="empty-state">
                    <div class="empty-icon"></div>
                    <h3>No results found</h3>
                    <p>Try different keywords or remove some filters.</p>
                    <a href="{{ route('home') }}" class="btn btn-primary">Browse All Research</a>
                </div>
            @else
                <div class="research-grid">
                    @foreach($researches as $item)
                        @include('components.research-card', ['research' => $item])
                    @endforeach
                </div>
                <div class="pagination-wrap">
                    {{ $researches->links() }}
                </div>
            @endif

        @else
            <!-- Featured Research -->
            <h2 class="section-title">Featured Research</h2>
            <div class="research-grid">
                @forelse($featured as $item)
                    @include('components.research-card', ['research' => $item])
                @empty
                    <div class="empty-state">
                        <p>No research available yet.</p>
                        @auth <a href="{{ route('research.submit') }}" class="btn btn-primary">Be the first to submit!</a> @endauth
                    </div>
                @endforelse
            </div>

            @if($researches->total() > 0)
                <h2 class="section-title" style="margin-top:40px">Recent Submissions</h2>
                <div class="research-grid">
                    @foreach($researches->take(6) as $item)
                        @include('components.research-card', ['research' => $item])
                    @endforeach
                </div>
                <div class="pagination-wrap">
                    {{ $researches->links() }}
                </div>
            @endif
        @endif
    </main>
</div>

@endsection