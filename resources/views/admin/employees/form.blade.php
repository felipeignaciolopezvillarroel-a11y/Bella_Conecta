@csrf

<div class="space-y-5">

    {{-- Nombre --}}
    <div>
        <label class="block text-sm font-semibold text-[#1F1B1A] mb-2">Nombre completo <span class="text-pink-600">*</span></label>
        <input type="text" name="name" id="name" value="{{ old('name', $employee->name ?? '') }}" required
            placeholder="Ej: Sofía Ramírez"
            class="w-full px-4 py-3 rounded-xl border border-[#EEE3DD] focus:border-pink-400 focus:ring-2 focus:ring-pink-200 outline-none transition text-sm">
    </div>

    {{-- Rol de sistema y Especialidad --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

        {{-- ROL DE SISTEMA (Dropdown Custom) --}}
        <div x-data="{ 
                open: false, 
                selected: '{{ old('system_role', $employee->user->system_role ?? '') }}', 
                options: { 'empleado': 'Empleado', 'admin': 'Administrador' } 
             }"
            class="relative">

            <label class="block text-sm font-semibold text-[#1F1B1A] mb-2">Rol de sistema <span class="text-pink-600">*</span></label>

            {{-- Input oculto para enviar el valor --}}
            <input type="hidden" name="system_role" x-model="selected">

            {{-- Botón que muestra la opción seleccionada --}}
            <button type="button" @click="open = !open" @click.away="open = false"
                class="cursor-pointer w-full px-4 py-3 rounded-xl border @error('system_role') border-red-400 focus:ring-red-200 @else border-[#EEE3DD] focus:border-pink-400 focus:ring-pink-200 @enderror outline-none transition text-sm bg-white text-left flex items-center justify-between">
                <span x-text="selected ? options[selected] : 'Selecciona una opción'" :class="!selected ? 'text-gray-400' : 'text-[#1F1B1A]'"></span>
                <svg class="w-4 h-4 text-gray-500 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            {{-- Menú desplegable --}}
            <div x-show="open" x-transition.opacity.duration.200ms class="absolute z-50 w-full mt-1 bg-white border border-[#EEE3DD] rounded-xl shadow-lg overflow-hidden">
                <template x-for="(label, value) in options" :key="value">
                    <button type="button" @click="selected = value; open = false"
                        class="w-full text-left px-4 py-3 text-sm transition"
                        :class="selected === value ? 'bg-pink-100 text-pink-700 font-semibold' : 'text-gray-700 hover:bg-pink-50'"
                        x-text="label">
                    </button>
                </template>
            </div>
        </div>

        {{-- ESPECIALIDAD (Select) --}}
        <div>
            <label class="block text-sm font-semibold text-[#1F1B1A] mb-2">Especialidad (para mostrar en la web)</label>
            <select name="role" class="w-full px-4 py-3 rounded-xl border border-[#EEE3DD] focus:border-pink-400 focus:ring-2 focus:ring-pink-200 outline-none transition text-sm bg-white">
                <option value="">Selecciona una especialidad</option>
                @php
                $especialidades = ['Colorista', 'Estilista', 'Barbero', 'Manicurista', 'Maquillador/a', 'Esteticista', 'Peinador/a', 'Recepcionista', 'Administrador'];
                $currentRole = old('role', $employee->role ?? '');
                @endphp
                @foreach($especialidades as $esp)
                <option value="{{ $esp }}" {{ $currentRole === $esp ? 'selected' : '' }}>{{ $esp }}</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- SERVICIOS QUE REALIZA (Solo si es profesional) --}}
    <div x-data="{ 
            selectedServices: {{ json_encode(old('services', isset($employee) && $employee ? $employee->services->pluck('id')->toArray() : [])) }},
            isProfessional: '{{ old('category', $employee->category ?? 'profesional') }}' === 'profesional'
         }"
        x-init="$watch('isProfessional', value => { if(!value) selectedServices = [] })">

        <div class="mt-5 border-t border-gray-100 pt-5" x-show="isProfessional">
            <div class="flex items-center justify-between mb-2">
                <label class="block text-sm font-semibold text-[#1F1B1A]">Servicios que realiza <span class="text-pink-600">*</span></label>
                {{-- Contador de seleccionados --}}
                <span class="text-xs font-semibold text-pink-600 bg-pink-50 px-2.5 py-1 rounded-full"
                    x-text="selectedServices.length + ' seleccionados'"></span>
            </div>

            <p class="text-xs text-gray-500 mb-3">Selecciona todos los servicios que este profesional puede realizar. Los clientes solo lo verán disponible para estos servicios.</p>

            {{-- Contenedor con altura máxima y scroll --}}
            <div class="grid grid-cols-2 md:grid-cols-3 gap-2 p-4 bg-[#FDF8F5] border border-[#EEE3DD] rounded-xl max-h-64 overflow-y-auto scrollbar-hide">
                @foreach(\App\Models\Service::where('active', true)->orderBy('name')->get() as $service)
                <label class="flex items-center gap-2 p-2 rounded-lg hover:bg-white transition cursor-pointer border border-transparent hover:border-[#EEE3DD]">
                    <input type="checkbox"
                        name="services[]"
                        value="{{ $service->id }}"
                        x-model="selectedServices"
                        class="rounded border-[#EEE3DD] text-pink-600 shadow-sm focus:ring-pink-500 shrink-0">
                    <span class="text-sm text-[#1F1B1A] truncate">
                        {{ $service->icon }} {{ $service->name }}
                    </span>
                </label>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Categoría (Dropdown Custom) --}}
    <div x-data="{ 
            open: false, 
            selected: '{{ old('category', $employee->category ?? '') }}', 
            options: { 'profesional': 'Profesional (visible en la web)', 'administrativo': 'Administrativo (solo interno)' } 
         }"
        class="relative">

        <label class="block text-sm font-semibold text-[#1F1B1A] mb-2">Categoría <span class="text-pink-600">*</span></label>

        {{-- Input oculto --}}
        <input type="hidden" name="category" x-model="selected">

        {{-- Botón --}}
        <button type="button" @click="open = !open" @click.away="open = false"
            class="cursor-pointer w-full px-4 py-3 rounded-xl border @error('category') border-red-400 focus:ring-red-200 @else border-[#EEE3DD] focus:border-pink-400 focus:ring-pink-200 @enderror outline-none transition text-sm bg-white text-left flex items-center justify-between">
            <span x-text="selected ? options[selected] : 'Selecciona una opción'" :class="!selected ? 'text-gray-400' : 'text-[#1F1B1A]'"></span>
            <svg class="w-4 h-4 text-gray-500 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </button>

        {{-- Menú desplegable --}}
        <div x-show="open" x-transition.opacity.duration.200ms class="absolute z-50 w-full mt-1 bg-white border border-[#EEE3DD] rounded-xl shadow-lg overflow-hidden">
            <template x-for="(label, value) in options" :key="value">
                <button type="button" @click="selected = value; open = false"
                    class="w-full text-left px-4 py-3 text-sm transition"
                    :class="selected === value ? 'bg-pink-100 text-pink-700 font-semibold' : 'text-gray-700 hover:bg-pink-50'"
                    x-text="label">
                </button>
            </template>
        </div>

        @error('category') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- Credenciales de acceso --}}
    <div class="mt-5 border-t border-gray-100 pt-5">
        <h3 class="text-sm font-bold text-[#1F1B1A] mb-3">Credenciales de acceso</h3>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-[#1F1B1A] mb-2">Correo electrónico <span class="text-pink-600">*</span></label>
                <input type="email" name="email" id="email"
                    value="{{ old('email', $employee->user->email ?? '') }}"
                    placeholder="empleado@bellaconecta.cl"
                    required
                    class="w-full px-4 py-3 rounded-xl border border-[#EEE3DD] focus:border-pink-400 focus:ring-2 focus:ring-pink-200 outline-none transition text-sm">
                @error('email') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-[#1F1B1A] mb-2">
                    Contraseña
                    @if(!isset($employee) || !$employee)
                    <span class="text-pink-600">*</span>
                    @else
                    <span class="text-xs text-gray-400 font-normal">(dejar en blanco para no cambiar)</span>
                    @endif
                </label>
                <input type="password" name="password" id="password"
                    placeholder="Mínimo 8 caracteres"
                    {{ (!isset($employee) || !$employee) ? 'required' : '' }}
                    class="w-full px-4 py-3 rounded-xl border border-[#EEE3DD] focus:border-pink-400 focus:ring-2 focus:ring-pink-200 outline-none transition text-sm">
                @error('password') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <p class="text-xs text-gray-500 mt-2">El empleado usará este correo y contraseña para acceder a su portal de trabajador.</p>
    </div>

    {{-- Biografía --}}
    <div>
        <label class="block text-sm font-semibold text-[#1F1B1A] mb-2">Biografía</label>
        <textarea name="bio" id="bio" rows="3"
            placeholder="Cuéntanos sobre su experiencia, especialidad y estilo..."
            class="w-full px-4 py-3 rounded-xl border border-[#EEE3DD] focus:border-pink-400 focus:ring-2 focus:ring-pink-200 outline-none transition text-sm resize-none">{{ old('bio', $employee->bio ?? '') }}</textarea>
        @error('bio') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- Foto --}}
    @php
    $currentPhotoUrl = null;
    if (isset($employee) && $employee && $employee->photo) {
    $currentPhotoUrl = asset('storage/' . $employee->photo);
    }
    @endphp

    <x-employee-avatar-upload
        name="photo_file"
        :current-photo="$currentPhotoUrl"
        shape="circle" />

    {{-- Estado --}}
    <div class="mt-5 border-t border-gray-100 pt-5">
        <label class="inline-flex items-center cursor-pointer">
            <input type="checkbox" name="active" value="1"
                class="rounded border-[#EEE3DD] text-pink-600 shadow-sm focus:ring-pink-500"
                {{ old('active', $employee->active ?? true) ? 'checked' : '' }}>
            <span class="ml-2 text-sm font-semibold text-[#1F1B1A]">Empleado activo</span>
        </label>
        <p class="text-xs text-gray-500 mt-1 ml-6">Si lo desmarcas, el empleado no aparecerá en la web pública ni podrá recibir reservas.</p>
    </div>

</div>