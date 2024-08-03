<ul class="space-y-4">
    @foreach($users as $user)
    <li class="px-4 py-4 bg-white border shadow-md rounded-xl md:bg-transparent md:shadow-none">
        <div class="flex flex-col items-center gap-4 md:flex-row md:items-center md:justify-between">
            <div class="flex-shrink-0">
                <img src="{{ asset('storage/' . $user->image) }}" alt="User Image" class="w-12 h-12 rounded-full md:w-12 md:h-12">
            </div>

            <div class="flex-grow text-center md:text-left">
                <h4 class="text-lg font-semibold md:text-base">{{ $user->name }}</h4>
                <p class="text-sm text-gray-500">{{ $user->email }}</p>
            </div>

            <div class="flex justify-center space-x-2 md:justify-end">
                <button class="text-blue-500 edit-btn" data-id="{{ $user->id }}">
                    <i class="fa-solid fa-pen-to-square"></i>
                </button>
                <button class="text-red-500 delete-btn" data-id="{{ $user->id }}">
                    <i class="fa-solid fa-trash"></i>
                </button>
            </div>
        </div>
    </li>
    @endforeach
</ul>