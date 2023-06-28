<x-telegram::layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Announcement') }}
            </h2>
            <div class="flex-col">
                <x-telegram::a-buttons.secondary href="{{ route('pozorprace_bot.announcement.create') }}" class="float-right">
                    {{ __("✚Add announcement") }}
                </x-telegram::a-buttons.secondary>
            </div>
        </div>
    </x-slot>
    <x-slot name="main">
        <x-telegram::white-block class="p-0">
            <x-telegram::search :action="route('pozorprace_bot.announcement.index')"/>
        </x-telegram::white-block>

        <x-telegram::white-block class="p-0">
            <x-telegram::table.table class="whitespace-nowrap">
                <x-telegram::table.thead>
                    <tr>
                        <x-telegram::table.th>id</x-telegram::table.th>
                        <x-telegram::table.th>Chat</x-telegram::table.th>
                        <x-telegram::table.th>City</x-telegram::table.th>
                        <x-telegram::table.th>Type</x-telegram::table.th>
                        <x-telegram::table.th>Announcement</x-telegram::table.th>
                        <x-telegram::table.th>Salary</x-telegram::table.th>
                        <x-telegram::table.th>Education</x-telegram::table.th>
                        <x-telegram::table.th>Workplace</x-telegram::table.th>
                        <x-telegram::table.th>Views</x-telegram::table.th>
                        <x-telegram::table.th>Status</x-telegram::table.th>
                        <x-telegram::table.th></x-telegram::table.th>
                    </tr>
                </x-telegram::table.thead>
                <x-telegram::table.tbody>
                    @forelse ($announcements_collection as $index => $announcement)
                        <tr class="@if($index % 2 === 0) bg-gray-100 @endif text-sm">
                            <x-telegram::table.td>{{ $announcement->id }}</x-telegram::table.td>
                            <x-telegram::table.td>
                                <x-telegram::chat.card :chat="$announcement->chat"/>
                            </x-telegram::table.td>
                            <x-telegram::table.td>{{ $announcement->city }}</x-telegram::table.td>
                            <x-telegram::table.td>{{ $announcement->type }}</x-telegram::table.td>
                            <x-telegram::table.td>
                                <p><b>{{ $announcement->title }}</b></p>
                                <p>{{ $announcement->caption }}</p>
                                <p><i>{{ $announcement->additional_info }}</i></p>
                            </x-telegram::table.td>
                            <x-telegram::table.td>
                                <p>{{ $announcement->salary }}</p>
                                <p>{{ $announcement->salary_type }}</p>
                            </x-telegram::table.td>
                            <x-telegram::table.td>{{ $announcement->education }}</x-telegram::table.td>
                            <x-telegram::table.td>{{ $announcement->workplace }}</x-telegram::table.td>
                            <x-telegram::table.td>{{ $announcement->views }}</x-telegram::table.td>
                            <x-telegram::table.td>
                                <x-telegram::badge color="{{$announcement->is_active ? 'green' : 'red'}}">
                                    {{ $announcement->status }}
                                </x-telegram::badge> 
                            </x-telegram::table.td>
                            <x-telegram::table.td>
                                <p>{{ $announcement->created_at->diffForHumans() }}</p>
                                <p>{{ $announcement->updated_at->diffForHumans() }}</p>
                            </x-telegram::table.td>
                            <x-telegram::table.buttons>
                                <x-telegram::a-buttons.primary href="{{ route('pozorprace_bot.announcement.edit', $announcement) }}">Edit</x-telegram::a-buttons.primary>
                                <form action="{{ route('pozorprace_bot.announcement.destroy', $announcement) }}" method="post" style="display: inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <x-telegram::buttons.danger>Delete</x-telegram::buttons.dangertton>
                                </form>
                            </x-telegram::table.buttons>
                        </tr>
                    @empty
                    @endforelse
                </x-telegram::table.tbody>
            </x-telegram::table.table>
        </x-telegram::white-block>
        <div class="mx-3">
            {{ $announcements->withQueryString()->links() }}
        </div>
    </x-slot>
</x-telegram::layout>