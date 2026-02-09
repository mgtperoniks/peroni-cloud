<x-app-layout>
    <x-slot name="header">
        Folders
    </x-slot>

    <x-slot name="headerActions">
        <a href="{{ route('folders.create') }}"
            class="flex items-center justify-center rounded-full w-10 h-10 bg-primary/10 text-primary hover:bg-primary/20 transition-colors">
            <span class="material-symbols-outlined text-[24px]">create_new_folder</span>
        </a>
    </x-slot>

    <div class="p-4" x-data="{ 
        showEditModal: false, 
        showDeleteModal: false, 
        activeFolder: { id: null, name: '' },
        openEdit(id, name) {
            this.activeFolder = { id, name };
            this.showEditModal = true;
        },
        openDelete(id, name) {
            this.activeFolder = { id, name };
            this.showDeleteModal = true;
        }
    }">
        @if($folders->isEmpty())
            <div class="flex flex-col items-center justify-center py-32 text-slate-300">
                <span class="material-symbols-outlined text-7xl mb-4 opacity-20">folder_off</span>
                <p class="font-black uppercase tracking-[0.2em] text-xs">No folders yet</p>
                <p class="text-[10px] mt-1 font-bold">Create folders to organize your documentation</p>
            </div>
        @else
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                @foreach($folders as $folder)
                    <div class="relative group">
                        <a href="{{ route('folders.show', $folder) }}"
                            class="flex flex-col gap-3 p-4 bg-white dark:bg-slate-900 rounded-[24px] shadow-sm hover:shadow-xl hover:scale-[1.02] transition-all border border-slate-50 dark:border-slate-800 h-full">
                            <div class="flex items-center justify-between">
                                <span class="material-symbols-outlined text-4xl text-amber-500 filled-icon">folder</span>
                                <span class="text-[10px] font-black text-slate-300">{{ $folder->photos_count }} Items</span>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-900 dark:text-white truncate">{{ $folder->name }}</p>
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mt-0.5">
                                    {{ $folder->created_at->format('d M Y') }}
                                </p>
                            </div>
                        </a>

                        <!-- Options Overlay (Only visible on hover) -->
                        <div class="absolute top-3 right-3 flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                            <button @click.prevent="openEdit('{{ $folder->id }}', '{{ addslashes($folder->name) }}')"
                                class="p-1.5 rounded-lg bg-white/90 dark:bg-slate-800/90 text-slate-600 dark:text-slate-400 hover:text-primary transition-colors shadow-sm">
                                <span class="material-symbols-outlined text-lg">edit</span>
                            </button>
                            <button @click.prevent="openDelete('{{ $folder->id }}', '{{ addslashes($folder->name) }}')"
                                class="p-1.5 rounded-lg bg-white/90 dark:bg-slate-800/90 text-slate-600 dark:text-slate-400 hover:text-red-500 transition-colors shadow-sm">
                                <span class="material-symbols-outlined text-lg">delete</span>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Edit Modal -->
        <template x-teleport="body">
            <div x-show="showEditModal"
                class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
                x-cloak>
                <div @click.away="showEditModal = false"
                    class="bg-white dark:bg-slate-900 rounded-[32px] p-8 w-full max-w-sm shadow-2xl border border-slate-100 dark:border-slate-800">
                    <h3 class="text-xl font-bold mb-6">Rename Folder</h3>
                    <form :action="'{{ url('folders') }}/' + activeFolder.id" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="space-y-4">
                            <input name="name" x-model="activeFolder.name" type="text" required
                                class="w-full h-14 rounded-2xl border-none bg-slate-50 dark:bg-slate-800 px-6 font-medium focus:ring-2 focus:ring-primary/50 transition-all">
                            <div class="flex gap-3 pt-2">
                                <button type="button" @click="showEditModal = false"
                                    class="flex-1 h-12 rounded-xl border border-slate-200 dark:border-slate-700 font-bold text-sm">Cancel</button>
                                <button type="submit"
                                    class="flex-[2] h-12 rounded-xl bg-primary text-white font-bold text-sm shadow-lg shadow-primary/20">Save
                                    Changes</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </template>

        <!-- Delete Modal -->
        <template x-teleport="body">
            <div x-show="showDeleteModal"
                class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
                x-cloak>
                <div @click.away="showDeleteModal = false"
                    class="bg-white dark:bg-slate-900 rounded-[32px] p-8 w-full max-w-sm shadow-2xl border border-slate-100 dark:border-slate-800">
                    <div class="flex items-center gap-4 mb-6 text-red-500">
                        <span class="material-symbols-outlined text-4xl">warning</span>
                        <h3 class="text-xl font-bold">Delete Folder?</h3>
                    </div>
                    <p class="text-slate-600 dark:text-slate-400 text-sm mb-8">
                        Are you sure you want to delete <span class="font-bold text-slate-900 dark:text-white"
                            x-text="activeFolder.name"></span>?
                        Photos inside will remain but will be uncategorized.
                    </p>
                    <form :action="'{{ url('folders') }}/' + activeFolder.id" method="POST">
                        @csrf
                        @method('DELETE')
                        <div class="flex gap-3">
                            <button type="button" @click="showDeleteModal = false"
                                class="flex-1 h-12 rounded-xl border border-slate-200 dark:border-slate-700 font-bold text-sm">Cancel</button>
                            <button type="submit"
                                class="flex-[2] h-12 rounded-xl bg-red-500 text-white font-bold text-sm shadow-lg shadow-red-500/20">Delete
                                Folder</button>
                        </div>
                    </form>
                </div>
            </div>
        </template>
    </div>
</x-app-layout>