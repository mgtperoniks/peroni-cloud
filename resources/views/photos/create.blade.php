<x-app-layout>
    <x-slot name="hideNav">true</x-slot>

    <x-slot name="header">
        Upload Documentation
    </x-slot>

    <x-slot name="headerActions">
        <a href="{{ route('photos.index') }}"
            class="flex items-center justify-center rounded-full w-10 h-10 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
            <span class="material-symbols-outlined">close</span>
        </a>
    </x-slot>

    <div class="flex flex-col flex-1 pb-40">
        <form action="{{ route('photos.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- EmptyState / Upload Area -->
            <div class="flex flex-col p-4 pt-6">
                <label for="photos-input"
                    class="group flex flex-col items-center gap-8 rounded-[32px] border-2 border-dashed border-primary/20 bg-primary/5 px-6 py-12 dark:border-primary/20 dark:bg-primary/5 cursor-pointer hover:bg-primary/10 transition-all">
                    <div class="flex flex-col items-center gap-4">
                        <div
                            class="size-20 rounded-3xl bg-primary shadow-xl shadow-primary/30 flex items-center justify-center text-white group-hover:scale-110 transition-transform">
                            <span class="material-symbols-outlined text-5xl">add_a_photo</span>
                        </div>
                        <div class="flex flex-col items-center gap-1">
                            <p class="text-slate-900 dark:text-white text-xl font-bold leading-tight text-center">
                                Capture Photos</p>
                            <p class="text-slate-500 dark:text-gray-400 text-sm font-medium text-center">Tap to open
                                camera or browse</p>
                        </div>
                    </div>
                    <div
                        class="flex min-w-[160px] items-center justify-center rounded-2xl h-14 px-8 bg-white dark:bg-slate-800 text-primary border border-primary/20 text-sm font-bold tracking-widest uppercase shadow-sm">
                        Select Files
                    </div>
                    <input id="photos-input" name="photos[]" type="file" multiple class="hidden"
                        accept="image/jpeg,image/png,image/webp" />
                </label>
            </div>

            <!-- MetaText -->
            <div class="px-4 pb-6">
                <div class="flex items-center justify-center gap-6 text-slate-400">
                    <div class="flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-lg">filter_none</span>
                        <p class="text-[10px] font-bold uppercase tracking-wider">Max 10 photos</p>
                    </div>
                    <div class="h-1 w-1 rounded-full bg-slate-300"></div>
                    <div class="flex items-center gap-1.5 text-green-600">
                        <span class="material-symbols-outlined text-lg">verified_user</span>
                        <p class="text-[10px] font-bold uppercase tracking-wider">SECURE UPLOAD</p>
                    </div>
                </div>
            </div>

            <!-- Form Section -->
            <div class="flex flex-col gap-4 px-4">
                <!-- Date -->
                <div class="flex flex-col gap-2">
                    <p
                        class="text-slate-900 dark:text-white text-[10px] font-black uppercase tracking-[0.2em] px-2 pl-4">
                        Record Date</p>
                    <input name="photo_date" type="date" value="{{ date('Y-m-d') }}"
                        class="w-full rounded-2xl border-none bg-white dark:bg-slate-900 shadow-xl shadow-slate-200/50 dark:shadow-none h-16 px-6 font-bold text-slate-900 dark:text-white focus:ring-2 focus:ring-primary/50 transition-all" />
                </div>

                <!-- Location -->
                <div class="flex flex-col gap-2">
                    <p
                        class="text-slate-900 dark:text-white text-[10px] font-black uppercase tracking-[0.2em] px-2 pl-4">
                        Factory Zone</p>
                    <select name="location" required
                        class="w-full rounded-2xl border-none bg-white dark:bg-slate-900 shadow-xl shadow-slate-200/50 dark:shadow-none h-16 px-6 font-bold text-slate-900 dark:text-white focus:ring-2 focus:ring-primary/50 transition-all appearance-none">
                        <option disabled selected value="">Select location</option>
                        <option value="Kantor">Kantor</option>
                        <option value="Bahan Baku">Bahan Baku</option>
                        <option value="Cor Flange">Cor Flange</option>
                        <option value="Netto Flange">Netto Flange</option>
                        <option value="Produksi Bubut">Produksi Bubut</option>
                        <option value="Cetak/Cor Fitting">Cetak/Cor Fitting</option>
                        <option value="Netto Fitting">Netto Fitting</option>
                        <option value="Gudang Jadi FL">Gudang Jadi FL</option>
                        <option value="Gudang Jadi PF">Gudang Jadi PF</option>
                        <option value="Aluminium">Aluminium</option>
                        <option value="Produksi Besi">Produksi Besi</option>
                        <option value="Satpam">Satpam</option>
                        <option value="Sparepart dan BP">Sparepart dan BP</option>
                    </select>
                </div>

                <!-- Folder Selection -->
                <div class="flex flex-col gap-2" x-data="{ 
                    showNewFolderModal: false, 
                    newFolderName: '', 
                    folders: @js($folders), 
                    selectedFolder: @js(request('folder_id', '')),
                    async createFolder() {
                        if (!this.newFolderName) return;
                        try {
                            const response = await fetch('{{ route('folders.store') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({ name: this.newFolderName })
                            });
                            
                            if (!response.ok) {
                                const errorText = await response.text();
                                console.error('Server error:', errorText);
                                throw new Error('Server returned ' + response.status);
                            }

                            const data = await response.json();
                            if (data.success) {
                                this.folders.push(data.folder);
                                this.selectedFolder = data.folder.id;
                                this.showNewFolderModal = false;
                                this.newFolderName = '';
                            } else {
                                alert('Error: ' + (data.message || 'Gagal membuat folder'));
                            }
                        } catch (e) {
                            console.error('Folder Creation Error:', e);
                            alert('Gagal membuat folder. Pastikan koneksi stabil atau coba gunakan menu Manage di samping.');
                        }
                    }
                }">
                    <div class="flex items-center justify-between px-2 pl-4">
                        <p class="text-slate-900 dark:text-white text-[10px] font-black uppercase tracking-[0.2em]">
                            Documentation Folder</p>
                        <div class="flex gap-4">
                            <a href="{{ route('folders.index') }}" target="_blank"
                                class="text-slate-400 text-[10px] font-bold uppercase tracking-wider flex items-center gap-1">
                                <span class="material-symbols-outlined text-sm">settings</span>
                                Manage
                            </a>
                            <button type="button" @click="showNewFolderModal = true"
                                class="text-primary text-[10px] font-bold uppercase tracking-wider flex items-center gap-1">
                                <span class="material-symbols-outlined text-sm">add</span>
                                New Folder
                            </button>
                        </div>
                    </div>
                    <select name="folder_id" x-model="selectedFolder"
                        class="w-full rounded-2xl border-none bg-white dark:bg-slate-900 shadow-xl shadow-slate-200/50 dark:shadow-none h-16 px-6 font-bold text-slate-900 dark:text-white focus:ring-2 focus:ring-primary/50 transition-all appearance-none">
                        <option value="">No Folder (Uncategorized)</option>
                        <template x-for="folder in folders" :key="folder.id">
                            <option :value="folder.id" x-text="folder.name" :selected="folder.id == selectedFolder">
                            </option>
                        </template>
                    </select>

                    <!-- New Folder Modal -->
                    <template x-teleport="body">
                        <div x-show="showNewFolderModal"
                            class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
                            x-cloak>
                            <div @click.away="showNewFolderModal = false"
                                class="bg-white dark:bg-slate-900 rounded-[32px] p-8 w-full max-w-sm shadow-2xl border border-slate-100 dark:border-slate-800">
                                <h3 class="text-xl font-bold mb-6">Create New Folder</h3>
                                <div class="space-y-4">
                                    <input x-model="newFolderName" @keydown.enter.prevent="createFolder()" type="text"
                                        placeholder="Folder name..."
                                        class="w-full h-14 rounded-2xl border-none bg-slate-50 dark:bg-slate-800 px-6 font-medium focus:ring-2 focus:ring-primary/50 transition-all">
                                    <div class="flex gap-3 pt-2">
                                        <button type="button" @click="showNewFolderModal = false"
                                            class="flex-1 h-12 rounded-xl border border-slate-200 dark:border-slate-700 font-bold text-sm">Cancel</button>
                                        <button type="button" @click="createFolder()"
                                            class="flex-[2] h-12 rounded-xl bg-primary text-white font-bold text-sm shadow-lg shadow-primary/20">Create
                                            Folder</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Department -->
                <div class="flex flex-col gap-2" x-data="{ 
                    role: '{{ auth()->user()->role }}',
                    roleMap: {
                        'adminppic': 'PPIC',
                        'adminqcfitting': 'QC Fitting',
                        'adminqcflange': 'QC Flange',
                        'qcinspektorpd': 'QC Inspektor PD',
                        'qcinspectorfl': 'QC Inspector FL',
                        'admink3': 'K3',
                        'sales': 'Sales',
                        'adminsparepart': 'Sparepart',
                        'mr': 'MR',
                        'direktur': 'Direktur'
                    },
                    selectedDept: '',
                    init() {
                        this.selectedDept = this.roleMap[this.role] || '';
                    }
                }">
                    <p
                        class="text-slate-900 dark:text-white text-[10px] font-black uppercase tracking-[0.2em] px-2 pl-4">
                        Department</p>
                    <select name="department" required x-model="selectedDept" {{ !in_array(auth()->user()->role, ['direktur', 'mr']) ? 'readonly style=pointer-events:none' : '' }}
                        class="w-full rounded-2xl border-none bg-white dark:bg-slate-900 shadow-xl shadow-slate-200/50 dark:shadow-none h-16 px-6 font-bold text-slate-900 dark:text-white focus:ring-2 focus:ring-primary/50 transition-all appearance-none {{ !in_array(auth()->user()->role, ['direktur', 'mr']) ? 'opacity-70 bg-slate-50' : '' }}">
                        <option disabled value="">Select department</option>
                        <option value="PPIC">PPIC</option>
                        <option value="QC Fitting">QC Fitting</option>
                        <option value="QC Flange">QC Flange</option>
                        <option value="QC Inspektor PD">QC Inspektor PD</option>
                        <option value="QC Inspector FL">QC Inspector FL</option>
                        <option value="K3">K3</option>
                        <option value="Sales">Sales</option>
                        <option value="Sparepart">Sparepart</option>
                        <option value="MR">MR</option>
                        <option value="Direktur">Direktur</option>
                    </select>
                    @if(!in_array(auth()->user()->role, ['direktur', 'mr']))
                        <p class="text-[9px] text-slate-400 px-4 font-bold uppercase tracking-wider italic">Locked to your
                            department</p>
                    @endif
                </div>

                <!-- Notes -->
                <div class="flex flex-col gap-2">
                    <p
                        class="text-slate-900 dark:text-white text-[10px] font-black uppercase tracking-[0.2em] px-2 pl-4">
                        Analysis Notes</p>
                    <textarea name="notes"
                        class="w-full min-h-[140px] rounded-2xl border-none bg-white dark:bg-slate-900 shadow-xl shadow-slate-200/50 dark:shadow-none p-6 font-medium text-slate-600 dark:text-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-primary/50 transition-all"
                        placeholder="Describe findings, asset condition, or safety concerns..."></textarea>
                </div>
            </div>

            <!-- Sticky Submit Button -->
            <div
                class="fixed bottom-0 left-0 right-0 max-w-7xl mx-auto bg-white/90 dark:bg-background-dark/95 backdrop-blur-md border-t border-slate-100 dark:border-slate-800 p-4 z-50 md:rounded-b-3xl">
                <button type="submit"
                    class="flex w-full items-center justify-center rounded-2xl h-16 bg-primary text-white text-sm font-bold tracking-[0.2em] shadow-2xl shadow-primary/40 transition-all hover:scale-[1.01] active:scale-95 uppercase">
                    <span class="material-symbols-outlined mr-3 text-xl">file_upload</span>
                    Finalize Documentation
                </button>
            </div>
        </form>
    </div>
</x-app-layout>