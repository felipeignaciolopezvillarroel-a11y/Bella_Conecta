<div class="space-y-5">

    {{-- Nombre --}}
    <div>
        <label class="block text-sm font-semibold text-[#1F1B1A] mb-2">Nombre del Servicio <span class="text-pink-600">*</span></label>
        <input type="text" name="name" value="{{ old('name', $service->name ?? '') }}" required placeholder="Ej: Corte Varón"
            class="w-full px-4 py-3 rounded-xl border border-[#EEE3DD] focus:border-pink-400 focus:ring-2 focus:ring-pink-200 outline-none transition text-sm">
    </div>

    {{-- Descripción --}}
    <div>
        <label class="block text-sm font-semibold text-[#1F1B1A] mb-2">Descripción</label>
        <textarea placeholder="Ej: Servicio completo de embellecimiento y cuidado para tu cabello" name="description" rows="3" class="w-full px-4 py-3 rounded-xl border border-[#EEE3DD] focus:border-pink-400 focus:ring-2 focus:ring-pink-200 outline-none transition text-sm resize-none">{{ old('description', $service->description ?? '') }}</textarea>
    </div>

    <div class="grid grid-cols-2 gap-4">
        {{-- Icono (Emoji) --}}
        <div>
            <label class="block text-sm font-semibold text-[#1F1B1A] mb-2">Icono (Emoji) <span class="text-pink-600">*</span></label>
            <input type="text" name="icon" value="{{ old('icon', $service->icon ?? '') }}" required placeholder="Ej: 💇"
                class="w-full px-4 py-3 rounded-xl border border-[#EEE3DD] focus:border-pink-400 focus:ring-2 focus:ring-pink-200 outline-none transition text-sm">
            <p class="text-xs text-gray-500 mt-1">Pega un emoji de tu teclado.</p>
        </div>

        {{-- Duración --}}
        <div>
            <label class="block text-sm font-semibold text-[#1F1B1A] mb-2">Duración (minutos) <span class="text-pink-600">*</span></label>
            <input type="number" name="duration_minutes" value="{{ old('duration_minutes', $service->duration_minutes ?? 60) }}" required min="1"
                class="w-full px-4 py-3 rounded-xl border border-[#EEE3DD] focus:border-pink-400 focus:ring-2 focus:ring-pink-200 outline-none transition text-sm">
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4">
        {{-- Precio --}}
        <div>
            <label class="block text-sm font-semibold text-[#1F1B1A] mb-2">Precio (CLP) <span class="text-pink-600">*</span></label>
            <input type="number" name="price" value="{{ old('price', $service->price ?? 0) }}" required min="0"
                class="w-full px-4 py-3 rounded-xl border border-[#EEE3DD] focus:border-pink-400 focus:ring-2 focus:ring-pink-200 outline-none transition text-sm">
        </div>

        {{-- Estado --}}
        <div>
            <label class="block text-sm font-semibold text-[#1F1B1A] mb-2">Estado</label>
            <label class="inline-flex items-center mt-2 cursor-pointer">
                <input type="checkbox" name="active" value="1" class="rounded border-[#EEE3DD] text-pink-600 shadow-sm focus:ring-pink-500" {{ old('active', $service->active ?? true) ? 'checked' : '' }}>
                <span class="ml-2 text-sm text-gray-700">Servicio Activo</span>
            </label>
        </div>
    </div>

    {{-- Destacado --}}
    <div class="mt-5 border-t border-gray-100 pt-5">
        <label class="inline-flex items-center cursor-pointer">
            <input type="checkbox" name="featured" value="1" class="rounded border-[#EEE3DD] text-pink-600 shadow-sm focus:ring-pink-500" {{ old('featured', $service->featured ?? false) ? 'checked' : '' }}>
            <span class="ml-2 text-sm font-semibold text-[#1F1B1A]">Destacar en la página principal (Home)</span>
        </label>
        <p class="text-xs text-gray-500 mt-1 ml-6">Si lo marcas, este servicio aparecerá en la sección de "Servicios Destacados" de la página de inicio.</p>
    </div>

    {{-- Beneficios (Los 3 items) --}}
    <div class="mt-5 border-t border-gray-100 pt-5" x-data="{ features: {{ json_encode(old('features', $service->features ?? [['title' => '', 'subtitle' => '']])) }} }">
        <label class="block text-sm font-semibold text-[#1F1B1A] mb-3">Beneficios del Servicio (Máximo 3)</label>

        <template x-for="(feature, index) in features" :key="index">
            <div class="flex gap-3 mb-3">
                <div class="flex-1">
                    <input type="text" :name="'features['+index+'][title]'" x-model="feature.title" placeholder="Título (Ej: Lavado incluido)"
                        class="w-full px-3 py-2 rounded-lg border border-[#EEE3DD] focus:border-pink-400 outline-none text-sm">
                </div>
                <div class="flex-1">
                    <input type="text" :name="'features['+index+'][subtitle]'" x-model="feature.subtitle" placeholder="Subtítulo (Ej: Con productos profesionales)"
                        class="w-full px-3 py-2 rounded-lg border border-[#EEE3DD] focus:border-pink-400 outline-none text-sm">
                </div>
                <button type="button" @click="features.splice(index, 1)" class="text-red-500 hover:text-red-700" x-show="features.length > 1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            </div>
        </template>

        <button type="button" @click="features.push({title: '', subtitle: ''})" x-show="features.length < 3" class="text-sm text-pink-600 hover:text-pink-800 font-semibold mt-2">
            + Agregar beneficio
        </button>
    </div>

</div>