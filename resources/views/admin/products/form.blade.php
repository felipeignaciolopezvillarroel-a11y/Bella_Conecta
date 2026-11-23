<div class="space-y-5">

    {{-- Marca e Nombre --}}
    <div class="grid grid-cols-2 gap-4">

        {{-- Nombre --}}
        <div>
            <label class="block text-sm font-semibold text-[#1F1B1A] mb-2">Nombre del Producto <span class="text-pink-600">*</span></label>
            <input type="text" name="name" x-model="name" value="{{ old('name', $product->name ?? '') }}" required
                placeholder="Ej: Shampoo profesional"
                class="w-full px-4 py-3 rounded-xl border border-[#EEE3DD] focus:border-pink-400 focus:ring-2 focus:ring-pink-200 outline-none transition text-sm capitalize">
        </div>

        {{-- Marca --}}
        <div>
            <label class="block text-sm font-semibold text-[#1F1B1A] mb-2">Marca</label>
            <input type="text" name="brand" value="{{ old('brand', $product->brand ?? '') }}"
                placeholder="Ej: L'Oréal"
                class="w-full px-4 py-3 rounded-xl border border-[#EEE3DD] focus:border-pink-400 focus:ring-2 focus:ring-pink-200 outline-none transition text-sm">
        </div>
    </div>

    {{-- Descripción --}}
    <div>
        <label class="block text-sm font-semibold text-[#1F1B1A] mb-2">Descripción</label>
        <textarea name="description" rows="3" class="w-full px-4 py-3 rounded-xl border border-[#EEE3DD] focus:border-pink-400 focus:ring-2 focus:ring-pink-200 outline-none transition text-sm resize-none">{{ old('description', $product->description ?? '') }}</textarea>
    </div>

    {{-- Imagen del producto --}}
    <div>
        <label class="text-sm font-semibold text-[#1F1B1A] block mb-2">Imagen del producto</label>
        <div class="flex items-center justify-center w-full">
            <label class="flex flex-col rounded-xl border-2 border-dashed transition w-full h-32 p-6 group text-center cursor-pointer relative overflow-hidden"
                :class="image ? 'border-green-400 bg-green-50' : 'border-[#EEE3DD] hover:border-pink-400 bg-[#FDF8F5]'">

                <input type="file" name="image_file" id="image_file" accept="image/*"
                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20"
                    @change="
                        const file = $event.target.files[0];
                        if (file) {
                            image = URL.createObjectURL(file);
                        }
                    ">

                {{-- Estado: Sin imagen --}}
                <div x-show="!image" class="h-full w-full flex flex-col items-center justify-center space-y-2 pointer-events-none">
                    <div class="w-10 h-10 rounded-full bg-pink-100 flex items-center justify-center text-pink-600 group-hover:scale-110 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div class="text-sm text-gray-500">
                        <span class="font-semibold text-pink-600">Haz clic para subir</span> o arrastra una imagen
                    </div>
                    <p class="text-xs text-gray-400">JPG, PNG, WEBP (Máx. 5MB)</p>
                </div>

                {{-- Estado: Imagen cargada (Éxito) --}}
                <div x-show="image" class="h-full w-full flex flex-col items-center justify-center space-y-2 pointer-events-none">
                    <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div class="text-sm text-green-700 font-semibold">
                        ¡Imagen seleccionada!
                    </div>
                    <p class="text-xs text-green-600">Haz clic para cambiarla si lo deseas.</p>
                </div>

                {{-- Botón para quitar imagen --}}
                <button type="button" x-show="image"
                    @click.prevent="image = ''; document.getElementById('image_file').value = ''"
                    class="absolute top-2 right-2 bg-white/90 text-red-600 p-1.5 rounded-full shadow hover:bg-red-50 transition z-30 pointer-events-auto">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </label>
        </div>
        @error('image_file') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    {{-- Precio Normal y Descuento --}}
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-semibold text-[#1F1B1A] mb-2">Precio Normal (CLP) <span class="text-pink-600">*</span></label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-500 font-medium">$</span>
                <input type="text" name="price"
                    value="{{ old('price', $product->price ?? '') }}"
                    x-init="$el.value = priceRaw ? formatNumber(priceRaw) : ''"
                    @input="
                           let val = $event.target.value.replace(/\D/g, '');
                           priceRaw = val;
                           $event.target.value = formatNumber(val);
                       "
                    required inputmode="numeric"
                    class="w-full pl-8 pr-4 py-3 rounded-xl border border-[#EEE3DD] focus:border-pink-400 focus:ring-2 focus:ring-pink-200 outline-none transition text-sm font-semibold">
            </div>
        </div>
        <div>
            <label class="block text-sm font-semibold text-[#1F1B1A] mb-2">Precio con Descuento</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-500 font-medium">$</span>
                <input type="text" name="discount_price"
                    value="{{ old('discount_price', $product->discount_price ?? '') }}"
                    x-init="$el.value = discountRaw ? formatNumber(discountRaw) : ''"
                    @input="
                           let val = $event.target.value.replace(/\D/g, '');
                           discountRaw = val;
                           $event.target.value = formatNumber(val);
                       "
                    inputmode="numeric"
                    placeholder="Opcional"
                    class="w-full pl-8 pr-4 py-3 rounded-xl border border-[#EEE3DD] focus:border-pink-400 focus:ring-2 focus:ring-pink-200 outline-none transition text-sm font-semibold">
            </div>
        </div>
    </div>

    {{-- Stock y Estado --}}
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-semibold text-[#1F1B1A] mb-2">Stock <span class="text-pink-600">*</span></label>
            <input type="number" name="stock" value="{{ old('stock', $product->stock ?? 0) }}" required min="0"
                class="w-full px-4 py-3 rounded-xl border border-[#EEE3DD] focus:border-pink-400 focus:ring-2 focus:ring-pink-200 outline-none transition text-sm">
        </div>
        <div>
            <label class="block text-sm font-semibold text-[#1F1B1A] mb-2">Estado</label>
            <label class="inline-flex items-center mt-2 cursor-pointer">
                <input type="checkbox" name="active" value="1" class="rounded border-[#EEE3DD] text-pink-600 shadow-sm focus:ring-pink-500" {{ old('active', $product->active ?? true) ? 'checked' : '' }}>
                <span class="ml-2 text-sm text-gray-700">Producto Activo</span>
            </label>
        </div>
    </div>

    {{-- Destacado --}}
    <div class="mt-5 border-t border-gray-100 pt-5">
        <label class="inline-flex items-center cursor-pointer">
            <input type="checkbox" name="featured" value="1" class="rounded border-[#EEE3DD] text-pink-600 shadow-sm focus:ring-pink-500" {{ old('featured', $product->featured ?? false) ? 'checked' : '' }}>
            <span class="ml-2 text-sm font-semibold text-[#1F1B1A]">Destacar en la página principal (Home)</span>
        </label>
        <p class="text-xs text-gray-500 mt-1 ml-6">Si lo marcas, este producto aparecerá en la sección de "Productos Top".</p>
    </div>

</div>