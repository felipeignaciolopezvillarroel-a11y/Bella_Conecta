{{-- Toast notifications --}}
<div x-data="toastSystem()"
     x-init="init()"
     class="fixed top-4 right-4 z-[9999] flex flex-col gap-3 w-full max-w-sm pointer-events-none">

    <template x-for="toast in toasts" :key="toast.id">
        <div x-show="toast.visible"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-x-full"
             x-transition:enter-end="opacity-100 translate-x-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-x-0"
             x-transition:leave-end="opacity-0 translate-x-full"
             class="pointer-events-auto relative overflow-hidden rounded-2xl shadow-xl border backdrop-blur-sm bg-white/95"
             :class="{
                'border-green-200': toast.type === 'success',
                'border-red-200':   toast.type === 'error',
                'border-yellow-200':toast.type === 'warning',
                'border-blue-200':  toast.type === 'info'
             }">

            {{-- Barra lateral de color --}}
            <div class="absolute left-0 top-0 bottom-0 w-1.5"
                 :class="{
                    'bg-green-500':  toast.type === 'success',
                    'bg-red-500':    toast.type === 'error',
                    'bg-yellow-500': toast.type === 'warning',
                    'bg-blue-500':   toast.type === 'info'
                 }"></div>

            <div class="flex items-start gap-3 p-4 pl-5">

                {{-- Icono --}}
                <div class="shrink-0 w-8 h-8 rounded-full flex items-center justify-center"
                     :class="{
                        'bg-green-100 text-green-600':  toast.type === 'success',
                        'bg-red-100 text-red-600':      toast.type === 'error',
                        'bg-yellow-100 text-yellow-600':toast.type === 'warning',
                        'bg-blue-100 text-blue-600':    toast.type === 'info'
                     }">
                    <template x-if="toast.type === 'success'">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                    </template>
                    <template x-if="toast.type === 'error'">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </template>
                    <template x-if="toast.type === 'warning'">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </template>
                    <template x-if="toast.type === 'info'">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </template>
                </div>

                {{-- Contenido --}}
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-sm text-[#1F1B1A]"
                       x-text="toast.type === 'success' ? '¡Listo!' :
                               toast.type === 'error'   ? 'Error' :
                               toast.type === 'warning' ? 'Atención' : 'Información'"></p>
                    <p class="text-sm text-gray-600 mt-0.5 break-words" x-text="toast.message"></p>
                </div>

                {{-- Botón cerrar --}}
                <button @click="close(toast.id)"
                        class="shrink-0 w-6 h-6 rounded-full hover:bg-gray-100 flex items-center justify-center text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Barra de progreso --}}
            <div class="absolute bottom-0 left-0 right-0 h-1 bg-gray-100">
                <div class="h-full transition-all ease-linear"
                     :class="{
                        'bg-green-500':  toast.type === 'success',
                        'bg-red-500':    toast.type === 'error',
                        'bg-yellow-500': toast.type === 'warning',
                        'bg-blue-500':   toast.type === 'info'
                     }"
                     :style="`width: ${toast.progress}%`"></div>
            </div>
        </div>
    </template>
</div>

@push('scripts')
<script>
    function toastSystem() {
        return {
            toasts: [],
            nextId: 1,

            init() {
                // 🔒 SOLO la primera instancia se registra como singleton
                if (window.__toastSingleton) {
                    return; // esta instancia no hace nada
                }
                window.__toastSingleton = this;

                // Listener del evento global 'toast'
                window.addEventListener('toast', (e) => {
                    this.add(e.detail.message, e.detail.type || 'info', e.detail.duration || 5000);
                });

                // Mensajes de sesión de Laravel
                @if(session('success'))
                    this.add(@json(session('success')), 'success');
                @endif
                @if(session('error'))
                    this.add(@json(session('error')), 'error');
                @endif
                @if(session('warning'))
                    this.add(@json(session('warning')), 'warning');
                @endif
                @if(session('info'))
                    this.add(@json(session('info')), 'info');
                @endif

                // Errores de validación (solo si no hay session error)
                @if($errors->any() && !session('error'))
                    this.add(@json($errors->first()), 'error');
                @endif
            },

            add(message, type = 'info', duration = 5000) {
                // 🔒 Si no soy la instancia principal, no hago nada
                if (window.__toastSingleton && window.__toastSingleton !== this) {
                    return;
                }

                // 🔒 Anti-duplicado: si el mismo mensaje + tipo se agregó en <500ms, ignorar
                const now = Date.now();
                const duplicate = this.toasts.some(t =>
                    t.message === message &&
                    t.type === type &&
                    t.createdAt &&
                    (now - t.createdAt) < 500
                );
                if (duplicate) {
                    return;
                }

                const id = this.nextId++;
                this.toasts.push({
                    id, message, type, visible: true, progress: 100, createdAt: now
                });

                const start = Date.now();
                const interval = setInterval(() => {
                    const elapsed = Date.now() - start;
                    const toast = this.toasts.find(t => t.id === id);
                    if (!toast) { clearInterval(interval); return; }
                    toast.progress = Math.max(0, 100 - (elapsed / duration) * 100);
                    if (elapsed >= duration) {
                        clearInterval(interval);
                        this.close(id);
                    }
                }, 50);
            },

            close(id) {
                const toast = this.toasts.find(t => t.id === id);
                if (toast) {
                    toast.visible = false;
                    setTimeout(() => {
                        this.toasts = this.toasts.filter(t => t.id !== id);
                    }, 300);
                }
            }
        };
    }
</script>
@endpush