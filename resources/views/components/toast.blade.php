{{-- Toast notifications --}}
<div x-data="toastSystem()"
     x-init="init()"
     aria-live="polite"
     aria-atomic="true"
     class="fixed top-5 right-5 z-[9999] flex flex-col gap-3 w-full max-w-sm pointer-events-none">

    <template x-for="toast in toasts" :key="toast.id">
        <div x-show="toast.visible"
             @mouseenter="toast.paused = true"
             @mouseleave="toast.paused = false"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-x-6 scale-95"
             x-transition:enter-end="opacity-100 translate-x-0 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-x-0 scale-100"
             x-transition:leave-end="opacity-0 translate-x-6 scale-95"
             class="pointer-events-auto group relative overflow-hidden rounded-2xl bg-white/95 backdrop-blur-md shadow-[0_12px_32px_-8px_rgba(31,27,26,0.18)] border transition-colors"
             :class="{
                'border-emerald-200/90': toast.type === 'success',
                'border-rose-200/90':    toast.type === 'error',
                'border-amber-200/90':   toast.type === 'warning',
                'border-pink-200/90':    toast.type === 'info'
             }">

            {{-- Acento lateral sutil --}}
            <div class="absolute inset-y-0 left-0 w-1"
                 :class="{
                    'bg-emerald-500': toast.type === 'success',
                    'bg-rose-500':    toast.type === 'error',
                    'bg-amber-400':   toast.type === 'warning',
                    'bg-pink-500':    toast.type === 'info'
                 }"></div>

            <div class="flex items-start gap-3.5 px-4 py-3.5 pl-5">

                {{-- Badge de icono --}}
                <div class="shrink-0 mt-0.5 w-9 h-9 rounded-xl flex items-center justify-center ring-1 ring-inset"
                     :class="{
                        'bg-emerald-50 text-emerald-600 ring-emerald-200/70': toast.type === 'success',
                        'bg-rose-50 text-rose-600 ring-rose-200/70':          toast.type === 'error',
                        'bg-amber-50 text-amber-600 ring-amber-200/70':       toast.type === 'warning',
                        'bg-pink-50 text-pink-600 ring-pink-200/70':          toast.type === 'info'
                     }">
                    <template x-if="toast.type === 'success'">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </template>
                    <template x-if="toast.type === 'error'">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
                        </svg>
                    </template>
                    <template x-if="toast.type === 'warning'">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </template>
                    <template x-if="toast.type === 'info'">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </template>
                </div>

                {{-- Título y mensaje --}}
                <div class="flex-1 min-w-0 pr-1">
                    <p class="font-semibold text-sm leading-snug text-[#1F1B1A]"
                       x-text="toast.type === 'success' ? '¡Listo!' :
                               toast.type === 'error'   ? 'No se pudo completar' :
                               toast.type === 'warning' ? 'Atención' : 'Información'"></p>
                    <p class="text-[13px] leading-relaxed text-gray-600 mt-0.5 break-words"
                       x-text="toast.message"></p>
                </div>

                {{-- Botón cerrar --}}
                <button type="button"
                        @click="close(toast.id)"
                        aria-label="Cerrar notificación"
                        class="shrink-0 -mr-1 -mt-0.5 w-7 h-7 rounded-lg text-gray-400 hover:text-[#1F1B1A] hover:bg-[#FAF5F2] flex items-center justify-center transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Barra inferior de progreso --}}
            <div class="h-1 w-full bg-[#F5EFEA]">
                <div class="h-full transition-[width] duration-75 ease-linear rounded-r-full"
                     :class="{
                        'bg-emerald-500': toast.type === 'success',
                        'bg-rose-500':    toast.type === 'error',
                        'bg-amber-400':   toast.type === 'warning',
                        'bg-pink-500':    toast.type === 'info'
                     }"
                     :style="`width: ${toast.progress}%`"></div>
            </div>
        </div>
    </template>
</div>

@push('scripts')
@verbatim
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('toastSystem', () => ({
            toasts: [],
            nextId: 1,

            init() {
                // Escuchar SIEMPRE el evento, sin singleton
                window.addEventListener('toast', (e) => {
                    this.add(e.detail.message, e.detail.type || 'info', e.detail.duration || 5000);
                });

                // Los mensajes de sesión los inyecta el backend via un evento distinto
                const flashMessages = window.__flashMessages || [];
                flashMessages.forEach(m => this.add(m.message, m.type, 5000));
            },

            add(message, type = 'info', duration = 5000) {
                const now = Date.now();

                const duplicate = this.toasts.some(t =>
                    t.message === message &&
                    t.type === type &&
                    t.createdAt &&
                    (now - t.createdAt) < 500
                );
                if (duplicate) return;

                if (this.toasts.length >= 3) {
                    this.toasts.shift();
                }

                const id = this.nextId++;
                const tickMs = 50;
                this.toasts.push({
                    id,
                    message,
                    type,
                    visible: true,
                    paused: false,
                    progress: 100,
                    createdAt: now
                });

                let elapsed = 0;
                const interval = setInterval(() => {
                    const toast = this.toasts.find(t => t.id === id);
                    if (!toast) {
                        clearInterval(interval);
                        return;
                    }
                    if (!toast.paused) {
                        elapsed += tickMs;
                        toast.progress = Math.max(0, 100 - (elapsed / duration) * 100);
                        if (elapsed >= duration) {
                            clearInterval(interval);
                            this.removeToast(id);
                        }
                    }
                }, tickMs);
            },

            removeToast(id) {
                const toast = this.toasts.find(t => t.id === id);
                if (toast) {
                    toast.visible = false;
                    setTimeout(() => {
                        this.toasts = this.toasts.filter(t => t.id !== id);
                    }, 250);
                }
            }
        }));
    });
</script>
@endverbatim
@endpush