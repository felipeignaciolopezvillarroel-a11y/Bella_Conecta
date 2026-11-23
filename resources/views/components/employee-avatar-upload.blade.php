@props([
'name' => 'photo_file',
'currentPhoto' => null,
'shape' => 'circle', // 'circle' o 'square'
'sampleImage' => null, // opcional: ruta o URL de una imagen de ejemplo (ej. asset('images/ejemplo-retrato.jpg'))
])

@php
$shapeClass = $shape === 'circle' ? 'rounded-full' : 'rounded-2xl';
@endphp

<div
    x-data="avatarUploader({
        currentPhoto: '{{ $currentPhoto }}',
        inputName: '{{ $name }}',
        shape: '{{ $shape }}'
    })"
    class="space-y-4">

    <div class="flex flex-col sm:flex-row items-center gap-4">
        {{-- Preview + Botón Subir + Ejemplo de referencia al lado --}}
        <div class="flex flex-wrap items-center gap-4">
            <div class="w-20 h-20 {{ $shapeClass }} overflow-hidden bg-pink-50 border border-[#EEE3DD] flex items-center justify-center shrink-0">
                <template x-if="previewUrl">
                    <img :src="previewUrl" alt="Preview" class="w-full h-full object-cover object-top">
                </template>
                <template x-if="!previewUrl">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-8 text-pink-300">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                    </svg>
                </template>
            </div>

            <div class="flex-1 min-w-[180px]">
                <div class="flex flex-wrap items-center gap-3">
                    <button type="button" x-on:click="$refs.fileInput.click()" class="cursor-pointer inline-flex items-center gap-2 px-4 py-2.5 rounded-full bg-pink-50 hover:bg-pink-100 text-pink-700 text-sm font-semibold transition">
                        <span x-text="previewUrl ? 'Cambiar foto' : 'Subir foto'"></span>
                    </button>
                </div>

                <p class="text-xs text-gray-500 mt-2">JPG, PNG o WebP. Máx. 5 MB · Se exporta a 600×600 px.</p>
            </div>
        </div>

        {{-- Tarjeta pequeña de ejemplo de encuadre al lado de "Subir foto" --}}
        <div class="inline-flex items-center gap-2.5 px-3 py-1.5 rounded-2xl bg-gray-50 border border-gray-200">
            <div class="w-11 h-14 rounded-lg overflow-hidden bg-gray-200 border border-gray-300 shrink-0 flex items-center justify-center relative">
                @if($sampleImage)
                <img src="{{ $sampleImage }}" alt="Ejemplo de encuadre" class="w-full h-full object-cover object-top">
                @else
                {{-- Silueta de referencia con margen superior y hombros --}}
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-8 text-gray-500">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                </svg>
                @endif
            </div>
            <div class="leading-tight">
                <span class="block text-[11px] font-semibold text-gray-700">Ejemplo de foto</span>
                <span class="block text-[10px] text-gray-500">Retrato centrado · aire arriba</span>
            </div>
        </div>
    </div>

    <input type="file" accept="image/*" class="hidden" x-ref="fileInput" x-on:change="onFileChange($event)">
    <input type="hidden" :name="inputName" x-model="hiddenValue">

    {{-- Modal de recorte --}}
    <div x-show="modalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;">
        <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" x-on:click="closeModal()"></div>

        <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden">
            <div class="p-5 border-b border-gray-100">
                <h3 class="text-lg font-bold text-[#1F1B1A]">Ajusta tu foto</h3>
                <p class="text-sm text-gray-500 mt-0.5">Arrastra la imagen para centrarla y usa el zoom.</p>
            </div>

            {{-- Área de recorte --}}
            <div class="p-5">
                <div class="relative w-full h-[340px] bg-gray-950 rounded-2xl overflow-hidden flex items-center justify-center select-none">

                    {{-- Marco de recorte de 300x300 --}}
                    <div class="relative w-[300px] h-[300px] shrink-0">
                        <img
                            x-ref="cropImage"
                            :src="imageSrc"
                            alt="Crop"
                            draggable="false"
                            x-on:mousedown="startDrag($event)"
                            x-on:touchstart="startDrag($event)"
                            :style="`position: absolute; max-width: none; cursor: ${dragging ? 'grabbing' : 'grab'}; opacity: ${ready ? 1 : 0}; transition: opacity 0.15s; width: ${currentWidth()}px; height: ${currentHeight()}px; left: ${offsetX}px; top: ${offsetY}px;`">

                        {{-- Máscara oscura exterior + borde del área de recorte --}}
                        <div
                            class="absolute inset-0 pointer-events-none border-2 border-white/90 {{ $shape === 'circle' ? 'rounded-full' : 'rounded-xl' }}"
                            style="box-shadow: 0 0 0 9999px rgba(10, 15, 25, 0.65);">
                        </div>
                    </div>
                </div>

                {{-- Slider de zoom --}}
                <div class="mt-4">
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-sm font-semibold text-[#1F1B1A]">Zoom</label>
                        <span class="text-xs font-medium text-gray-500" x-text="Math.round(zoom * 100) + '%'"></span>
                    </div>
                    <input
                        type="range"
                        min="1"
                        max="3"
                        step="0.05"
                        x-model.number="zoom"
                        x-on:input="onZoomChange()"
                        class="w-full accent-pink-600">
                </div>
            </div>

            <div class="p-5 border-t border-gray-100 flex items-center justify-between gap-3">
                <button type="button" x-on:click="resetCrop()" class="cursor-pointer text-sm font-semibold text-gray-600 hover:text-[#1F1B1A] px-3 py-2">
                    Reiniciar
                </button>
                <div class="flex items-center gap-2">
                    <button type="button" x-on:click="closeModal()" class="cursor-pointer px-4 py-2.5 rounded-full text-sm font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 transition">
                        Cancelar
                    </button>
                    <button type="button" x-on:click="applyCrop()" class="cursor-pointer px-4 py-2.5 rounded-full text-sm font-semibold text-white bg-pink-600 hover:bg-pink-700 transition shadow-sm">
                        Aplicar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    window.avatarUploader = function({
        currentPhoto = '',
        inputName = 'photo_file',
        shape = 'circle'
    }) {
        return {
            previewUrl: currentPhoto || '',
            hiddenValue: '',
            inputName: inputName,
            shape: shape,
            modalOpen: false,
            imageSrc: '',
            imageEl: null,
            baseScale: 1,
            zoom: 1,
            offsetX: 0,
            offsetY: 0,
            ready: false,
            dragging: false,
            dragStart: {
                x: 0,
                y: 0
            },
            offsetStart: {
                x: 0,
                y: 0
            },
            cropSize: 300,

            init() {
                this.hiddenValue = '';
                window.addEventListener('mousemove', this.onMouseMove.bind(this));
                window.addEventListener('mouseup', this.onMouseUp.bind(this));
                window.addEventListener('touchmove', this.onTouchMove.bind(this), {
                    passive: false
                });
                window.addEventListener('touchend', this.onMouseUp.bind(this));
            },

            totalScale() {
                return this.baseScale * this.zoom;
            },

            currentWidth() {
                if (!this.imageEl) return this.cropSize;
                return this.imageEl.naturalWidth * this.totalScale();
            },

            currentHeight() {
                if (!this.imageEl) return this.cropSize;
                return this.imageEl.naturalHeight * this.totalScale();
            },

            async onFileChange(event) {
                const file = event.target.files[0];
                if (!file) return;

                if (file.size > 5 * 1024 * 1024) {
                    alert('La imagen supera el límite de 5 MB.');
                    event.target.value = '';
                    return;
                }

                const dataUrl = await new Promise((resolve) => {
                    const reader = new FileReader();
                    reader.onload = (e) => resolve(e.target.result);
                    reader.readAsDataURL(file);
                });

                const img = await new Promise((resolve) => {
                    const i = new Image();
                    i.onload = () => resolve(i);
                    i.src = dataUrl;
                });

                this.imageEl = img;
                this.ready = false;

                const minDim = Math.min(img.naturalWidth, img.naturalHeight);
                this.baseScale = this.cropSize / minDim;
                this.zoom = 1;

                this.centerImage();
                this.imageSrc = dataUrl;
                this.modalOpen = true;

                await this.$nextTick();
                this.ready = true;
                event.target.value = '';
            },

            centerImage() {
                if (!this.imageEl) return;
                this.zoom = 1;
                this.offsetX = (this.cropSize - this.currentWidth()) / 2;
                this.offsetY = (this.cropSize - this.currentHeight()) / 2;
                this.clampOffset();
            },

            onZoomChange() {
                if (!this.imageEl) return;

                const prevW = this.currentWidth();
                const prevH = this.currentHeight();

                const centerRatioX = (this.cropSize / 2 - this.offsetX) / prevW;
                const centerRatioY = (this.cropSize / 2 - this.offsetY) / prevH;

                const nextW = this.imageEl.naturalWidth * this.totalScale();
                const nextH = this.imageEl.naturalHeight * this.totalScale();

                this.offsetX = this.cropSize / 2 - centerRatioX * nextW;
                this.offsetY = this.cropSize / 2 - centerRatioY * nextH;

                this.clampOffset();
            },

            startDrag(event) {
                this.dragging = true;
                const clientX = event.touches ? event.touches[0].clientX : event.clientX;
                const clientY = event.touches ? event.touches[0].clientY : event.clientY;
                this.dragStart = {
                    x: clientX,
                    y: clientY
                };
                this.offsetStart = {
                    x: this.offsetX,
                    y: this.offsetY
                };
                event.preventDefault();
            },

            clampOffset() {
                if (!this.imageEl) return;

                const imgW = this.currentWidth();
                const imgH = this.currentHeight();

                if (imgW >= this.cropSize) {
                    this.offsetX = Math.max(this.cropSize - imgW, Math.min(0, this.offsetX));
                } else {
                    this.offsetX = (this.cropSize - imgW) / 2;
                }

                if (imgH >= this.cropSize) {
                    this.offsetY = Math.max(this.cropSize - imgH, Math.min(0, this.offsetY));
                } else {
                    this.offsetY = (this.cropSize - imgH) / 2;
                }
            },

            onMouseMove(e) {
                if (!this.dragging) return;
                this.offsetX = this.offsetStart.x + (e.clientX - this.dragStart.x);
                this.offsetY = this.offsetStart.y + (e.clientY - this.dragStart.y);
                this.clampOffset();
            },

            onTouchMove(e) {
                if (!this.dragging) return;
                this.offsetX = this.offsetStart.x + (e.touches[0].clientX - this.dragStart.x);
                this.offsetY = this.offsetStart.y + (e.touches[0].clientY - this.dragStart.y);
                this.clampOffset();
                e.preventDefault();
            },

            onMouseUp() {
                this.dragging = false;
            },

            resetCrop() {
                this.centerImage();
            },

            applyCrop() {
                if (!this.imageEl) return;

                const outputSize = 600;
                const scaleFactor = outputSize / this.cropSize;
                const drawW = this.currentWidth() * scaleFactor;
                const drawH = this.currentHeight() * scaleFactor;
                const drawX = this.offsetX * scaleFactor;
                const drawY = this.offsetY * scaleFactor;

                const canvas = document.createElement('canvas');
                canvas.width = outputSize;
                canvas.height = outputSize;
                const ctx = canvas.getContext('2d');

                ctx.imageSmoothingEnabled = true;
                ctx.imageSmoothingQuality = 'high';

                ctx.drawImage(this.imageEl, drawX, drawY, drawW, drawH);

                this.hiddenValue = canvas.toDataURL('image/jpeg', 0.92);

                canvas.toBlob((blob) => {
                    if (!blob) return;
                    if (this.previewUrl && this.previewUrl.startsWith('blob:')) {
                        URL.revokeObjectURL(this.previewUrl);
                    }
                    this.previewUrl = URL.createObjectURL(blob);
                }, 'image/jpeg', 0.92);

                this.closeModal();
            },

            closeModal() {
                this.modalOpen = false;
                this.dragging = false;
                this.ready = false;
            },
        };
    };
</script>
@endpush