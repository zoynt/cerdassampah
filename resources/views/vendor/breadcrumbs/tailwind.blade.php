@unless ($breadcrumbs->isEmpty())

    <nav aria-label="Breadcrumb" class="inline-block bg-white border border-gray-200 shadow-sm rounded-xl p-3 mb-6">
        <ol class="flex items-center space-x-1.5 text-sm">

            @foreach ($breadcrumbs as $breadcrumb)

                @if (!$loop->first)
                    <li>
                        <div class="flex items-center">
                            <svg class="h-5 w-5 flex-shrink-0 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </li>
                @endif

                <li>
                    @if ($breadcrumb->url && !$loop->last)
                        <a href="{{ $breadcrumb->url }}" class="font-medium text-gray-500 hover:text-green-600 transition duration-150 ease-in-out">

                            @if ($loop->first)
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M9.293 2.293a1 1 0 011.414 0l7 7A1 1 0 0117 11h-1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-3a1 1 0 00-1-1H9a1 1 0 00-1 1v3a1 1 0 01-1 1H5a1 1 0 01-1-1v-6H3a1 1 0 01-.707-1.707l7-7z" clip-rule="evenodd" />
                                </svg>
                                <span class="sr-only">Home</span>
                            @else
                                {{ $breadcrumb->title }}
                            @endif

                        </a>
                    @else
                        <span class="font-semibold text-gray-800" aria-current="page">

                             @if ($loop->first)
                                <svg class="h-5 w-5 text-gray-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M9.293 2.293a1 1 0 011.414 0l7 7A1 1 0 0117 11h-1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-3a1 1 0 00-1-1H9a1 1 0 00-1 1v3a1 1 0 01-1 1H5a1 1 0 01-1-1v-6H3a1 1 0 01-.707-1.707l7-7z" clip-rule="evenodd" />
                                </svg>
                                <span class="sr-only">Home</span>
                            @else
                                {{ $breadcrumb->title }}
                            @endif

                        </span>
                    @endif
                </li>

            @endforeach
        </ol>
    </nav>
@endunless
