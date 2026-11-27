<style>
    #faq-chatbot-widget {
        position: fixed;
        bottom: 20px;
        left: 20px;
        z-index: 40;
        font-family: 'Poppins', sans-serif;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
    }

    #faq-chatbot-panel {
        width: 360px;
        max-width: calc(100vw - 32px);
        max-height: calc(100dvh - 105px);
        margin-bottom: 12px;
        background: #FFFFFF;
        border: 1px solid #E5E7EB;
        border-radius: 16px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        overflow: hidden;
        flex-direction: column;
    }

    #faq-header {
        flex: 0 0 auto;
        background: #1F1B1A;
        color: #FFFFFF;
        padding: 14px 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
    }

    #faq-messages {
        flex: 0 0 auto;
        height: 310px;
        max-height: calc(100dvh - 235px);
        overflow-y: auto;
        overflow-x: hidden;
        padding: 14px;
        background: #FDF8F5;
        font-size: 13px;
        display: flex;
        flex-direction: column;
        gap: 10px;
        scrollbar-width: thin;
    }

    #faq-form {
        flex: 0 0 auto;
        padding: 10px;
        background: #FFFFFF;
        border-top: 1px solid #E5E7EB;
        display: flex;
        gap: 8px;
    }

    #faq-input {
        flex: 1;
        min-width: 0;
        border: 1px solid #D1D5DB;
        border-radius: 10px;
        padding: 8px 12px;
        font-size: 13px;
        outline: none;
    }

    @media (max-width: 480px) {
        #faq-chatbot-widget {
            bottom: 16px;
            left: 12px;
            right: 12px;
            align-items: flex-start;
        }

        #faq-chatbot-panel {
            width: 100%;
            max-width: 100%;
            max-height: calc(100dvh - 92px);
        }

        #faq-messages {
            height: 260px;
            max-height: calc(100dvh - 220px);
        }

        #faq-toggle-btn {
            padding: 15px !important;
        }
    }
</style>

<div id="faq-chatbot-widget" x-data="faqChatbot()">

    {{-- Ventana de preguntas frecuentes --}}
    <div id="faq-chatbot-panel"
        x-show="open"
        x-transition
        :style="open ? 'display: flex;' : 'display: none;'"
        style="display: none;">

        {{-- Cabecera fija --}}
        <div id="faq-header">
            <div style="min-width: 0;">
                <div style="font-size: 14px; font-weight: 600;">Asistente Bellas Conecta</div>
                <div style="font-size: 11px; color: #D1D5DB;">Guía rápida de servicios, productos y reservas</div>
            </div>
            <button type="button"
                id="faq-close-btn"
                @click="toggle(false)"
                style="flex-shrink: 0; background: transparent; border: none; color: #D1D5DB; font-size: 16px; cursor: pointer; padding: 4px 8px;">
                ✕
            </button>
        </div>

        {{-- Contenedor de altura fija con scroll vertical para respuestas y preguntas --}}
        <div id="faq-messages">

            <template x-for="msg in messages" :key="msg.id">
                <div :style="msg.from === 'user' ? 'display: flex; justify-content: flex-end; flex-shrink: 0;' : 'display: flex; justify-content: flex-start; flex-shrink: 0;'">
                    <div :style="msg.from === 'user'
                            ? 'background: #1F1B1A; color: #FFFFFF; padding: 9px 13px; border-radius: 14px 14px 4px 14px; max-width: 82%; line-height: 1.45; overflow-wrap: anywhere;'
                            : 'background: #FFFFFF; color: #2B2B2B; border: 1px solid #E5E7EB; padding: 9px 13px; border-radius: 14px 14px 14px 4px; max-width: 88%; line-height: 1.45; overflow-wrap: anywhere;'">
                        <div x-text="msg.text"></div>
                        <template x-if="msg.url">
                            <div style="margin-top: 8px;">
                                <a :href="msg.url"
                                    style="display: inline-block; background: #DB2777; color: #FFFFFF; text-decoration: none; font-size: 11px; font-weight: 600; padding: 5px 10px; border-radius: 8px;">
                                    <span x-text="msg.linkLabel || 'Ir a la sección'"></span> →
                                </a>
                            </div>
                        </template>
                    </div>
                </div>
            </template>

            {{-- Confirmación: ¿Necesitas ayuda con algo más? --}}
            <div id="faq-more-help-box"
                x-show="askMoreHelp"
                style="display: none; flex-shrink: 0; background: #FFFFFF; border: 1px solid #E5E7EB; border-radius: 14px 14px 14px 4px; padding: 10px 13px; max-width: 88%;">
                <div style="color: #2B2B2B; margin-bottom: 8px; line-height: 1.4;">
                    ¿Necesitas ayuda con algo más?
                </div>
                <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                    <button type="button"
                        id="faq-more-yes"
                        @click="confirmMoreHelp(true)"
                        style="background: #1F1B1A; color: #FFFFFF; border: none; border-radius: 8px; padding: 5px 14px; font-size: 12px; font-weight: 500; cursor: pointer;">
                        Sí
                    </button>
                    <button type="button"
                        id="faq-more-no"
                        @click="confirmMoreHelp(false)"
                        style="background: #F3F4F6; color: #2B2B2B; border: 1px solid #D1D5DB; border-radius: 8px; padding: 5px 14px; font-size: 12px; font-weight: 500; cursor: pointer;">
                        No
                    </button>
                </div>
            </div>

            {{-- Consultas rápidas --}}
            <div id="faq-quick-section" x-show="showQuickFaqs" style="flex-shrink: 0; margin-top: 4px;">
                <div style="font-size: 11px; font-weight: 600; color: #6B7280; text-transform: uppercase; margin-bottom: 8px;">
                    Consultas rápidas
                </div>
                <div id="faq-quick-buttons" style="display: flex; flex-wrap: wrap; gap: 6px;">
                    <template x-for="faq in faqs" :key="faq.id">
                        <button type="button"
                            @click="askFaq(faq)"
                            style="background: #FFFFFF; color: #2B2B2B; border: 1px solid #D1D5DB; border-radius: 10px; padding: 6px 10px; font-size: 12px; text-align: left; cursor: pointer; max-width: 100%;">
                            <span x-text="faq.question"></span>
                        </button>
                    </template>
                </div>
            </div>
        </div>

        {{-- Input fijo abajo --}}
        <form id="faq-form" @submit.prevent="submitQuestion">
            <input id="faq-input"
                type="text"
                x-model="input"
                placeholder="Pregunta por reservas, boleta, productos, equipo...">
            <button type="submit"
                style="flex-shrink: 0; background: #1F1B1A; color: #FFFFFF; border: none; border-radius: 10px; padding: 8px 14px; font-size: 13px; font-weight: 500; cursor: pointer;">
                Enviar
            </button>
        </form>
    </div>

    {{-- Botón flotante con ícono de robot --}}
    <div style="display: flex; justify-content: flex-start;">
        <button type="button"
            id="faq-toggle-btn"
            @click="toggle()"
            aria-label="Abrir asistente de preguntas frecuentes"
            style="background: #1F1B1A; color: #FFFFFF; border: none; border-radius: 9999px; padding: 18px; font-size: 13px; font-weight: 500; box-shadow: 0 12px 24px rgba(0, 0, 0, 0.25); cursor: pointer; display: inline-flex; align-items: center; gap: 8px;">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-7 lucide lucide-bot preview-icon">
                <path d="M12 8V4H8" />
                <rect width="16" height="12" x="4" y="8" rx="2" />
                <path d="M2 14h2" />
                <path d="M20 14h2" />
                <path d="M15 13v2" />
                <path d="M9 13v2" />
            </svg>
        </button>
    </div>
</div>

<script>
    (function() {
        const siteKnowledge = [
            {
                id: 'reservas',
                question: '¿Cómo agendo una hora?',
                keywords: ['hora', 'agendar', 'reservar', 'reserva', 'cita', 'turno', 'disponibilidad', 'horario'],
                answer: 'Puedes elegir un servicio o profesional y revisar horarios disponibles directamente en la sección de reservas.',
                url: @json(route('bookings.pick')),
                linkLabel: 'Ir a reservar'
            },
            {
                id: 'servicios',
                question: '¿Qué servicios ofrecen?',
                keywords: ['servicio', 'servicios', 'tratamiento', 'tratamientos', 'peinado', 'corte', 'color', 'belleza'],
                answer: 'Contamos con servicios activos publicados en el catálogo. Puedes ver el detalle de cada uno en Servicios.',
                url: @json(route('services.index')),
                linkLabel: 'Ver servicios'
            },
            {
                id: 'productos',
                question: '¿Cómo compro o reservo productos?',
                keywords: ['producto', 'productos', 'tienda', 'comprar', 'carrito', 'stock', 'precio', 'precios', 'destacados'],
                answer: 'Agrega productos al carrito desde la tienda o los destacados del home y genera tu boleta de retiro.',
                url: @json(route('products.index')),
                linkLabel: 'Ir a productos'
            },
            {
                id: 'boleta',
                question: '¿Cómo funciona la boleta y el retiro?',
                keywords: ['boleta', 'voleta', 'retiro', 'retirar', 'codigo', 'código', 'ticket', 'pagar', 'tienda fisica', 'tienda física'],
                answer: 'Al confirmar tu pedido se genera una boleta con código; preséntala al retirar y pagar en la tienda física.',
                url: @json(route('products.index')),
                linkLabel: 'Ver tienda'
            },
            {
                id: 'equipo',
                question: '¿Quiénes atienden en el equipo?',
                keywords: ['equipo', 'profesional', 'profesionales', 'estilista', 'estilistas', 'quienes', 'atiende'],
                answer: 'En la sección Equipo puedes conocer a los profesionales activos y elegir a quién agendar.',
                url: @json(route('team.index')),
                linkLabel: 'Conocer al equipo'
            },
            {
                id: 'mis-reservas',
                question: '¿Dónde veo o cancelo mis reservas?',
                keywords: ['mis reservas', 'cancelar', 'anular', 'historial', 'cuenta', 'sesion', 'sesión', 'login', 'ingresar'],
                answer: @json(auth()->check() ? 'Puedes revisar o gestionar tus reservas activas desde Mis reservas.' : 'Inicia sesión para consultar y administrar tus reservas.'),
                url: @json(auth()->check() ? route('bookings.index') : route('login')),
                linkLabel: @json(auth()->check() ? 'Ver mis reservas' : 'Iniciar sesión')
            }
        ];

        function normalizeText(str) {
            return (str || '')
                .toLowerCase()
                .normalize('NFD')
                .replace(/[\u0300-\u036f]/g, '')
                .replace(/[^a-z0-9\s]/g, ' ')
                .trim();
        }

        function buildAutonomousReply(rawQuery) {
            const clean = normalizeText(rawQuery);
            const tokens = clean.split(/\s+/).filter(Boolean);

            if (tokens.some(t => ['hola', 'buenas', 'holi', 'ey', 'menu', 'ayuda'].includes(t)) && tokens.length <= 3) {
                return {
                    text: '¡Hola! Puedo orientarte sobre servicios, productos, reservas, boletas de retiro o el equipo. ¿Qué necesitas consultar?',
                    url: null,
                    linkLabel: null
                };
            }

            const pageText = normalizeText(document.body?.innerText || '');

            let bestItem = null;
            let bestScore = 0;

            for (const item of siteKnowledge) {
                let score = 0;
                for (const kw of item.keywords) {
                    const nKw = normalizeText(kw);
                    if (clean.includes(nKw)) {
                        score += nKw.includes(' ') ? 5 : 3;
                    }
                    if (tokens.includes(nKw)) {
                        score += 2;
                    }
                }
                if (score > bestScore) {
                    bestScore = score;
                    bestItem = item;
                }
            }

            if (bestItem && bestScore > 0) {
                let extra = '';
                if (bestItem.id === 'productos' && pageText.includes('productos top')) {
                    extra = ' En el home también tienes acceso a los productos destacados.';
                }
                return {
                    text: bestItem.answer + extra,
                    url: bestItem.url,
                    linkLabel: bestItem.linkLabel
                };
            }

            return {
                text: 'No encontré un tema exacto para "' + rawQuery.trim() + '". Puedes consultar por reservas, servicios, productos, boleta o equipo.',
                url: @json(route('bookings.pick')),
                linkLabel: 'Ver opciones de reserva'
            };
        }

        window.faqChatbot = function() {
            return {
                open: false,
                input: '',
                nextId: 2,
                showQuickFaqs: true,
                askMoreHelp: false,
                messages: [{
                    id: 1,
                    from: 'bot',
                    text: '¡Hola! Soy el asistente de Bellas Conecta. Pregúntame sobre reservas, servicios, productos o retiro con boleta.',
                    url: null,
                    linkLabel: null
                }],
                faqs: siteKnowledge.slice(0, 4),

                toggle(forceState = null) {
                    this.open = typeof forceState === 'boolean' ? forceState : !this.open;
                    syncFallbackPanel(this.open);
                    if (this.open) {
                        this.$nextTick(() => scrollFaqBottom());
                    }
                },

                askFaq(faq) {
                    this.showQuickFaqs = false;
                    this.askMoreHelp = false;

                    this.messages.push({
                        id: this.nextId++,
                        from: 'user',
                        text: faq.question
                    });
                    this.messages.push({
                        id: this.nextId++,
                        from: 'bot',
                        text: faq.answer,
                        url: faq.url || null,
                        linkLabel: faq.linkLabel || null
                    });

                    this.askMoreHelp = true;
                    this.$nextTick(() => scrollFaqBottom());
                },

                submitQuestion() {
                    const query = this.input.trim();
                    if (!query) return;

                    this.showQuickFaqs = false;
                    this.askMoreHelp = false;

                    this.messages.push({
                        id: this.nextId++,
                        from: 'user',
                        text: query
                    });
                    this.input = '';

                    const reply = buildAutonomousReply(query);
                    this.messages.push({
                        id: this.nextId++,
                        from: 'bot',
                        text: reply.text,
                        url: reply.url,
                        linkLabel: reply.linkLabel
                    });

                    this.askMoreHelp = true;
                    this.$nextTick(() => scrollFaqBottom());
                },

                confirmMoreHelp(needsMore) {
                    this.askMoreHelp = false;

                    if (needsMore) {
                        this.messages.push({
                            id: this.nextId++,
                            from: 'user',
                            text: 'Sí'
                        });
                        this.messages.push({
                            id: this.nextId++,
                            from: 'bot',
                            text: 'Claro, elige una consulta rápida o escribe otra pregunta.'
                        });
                        this.showQuickFaqs = true;
                        this.$nextTick(() => scrollFaqBottom());
                    } else {
                        this.messages.push({
                            id: this.nextId++,
                            from: 'user',
                            text: 'No'
                        });
                        this.messages.push({
                            id: this.nextId++,
                            from: 'bot',
                            text: '¡Perfecto! Si necesitas algo más, estaré aquí.'
                        });
                        this.showQuickFaqs = true;
                        this.$nextTick(() => scrollFaqBottom());

                        setTimeout(() => {
                            this.toggle(false);
                        }, 650);
                    }
                }
            };
        };

        function scrollFaqBottom() {
            const box = document.getElementById('faq-messages');
            if (box) {
                box.scrollTop = box.scrollHeight;
            }
        }

        function syncFallbackPanel(isOpen) {
            const panel = document.getElementById('faq-chatbot-panel');
            if (!panel) return;
            panel.style.display = isOpen ? 'flex' : 'none';
        }

        // Respaldo vanilla si Alpine no estuviera montado
        document.addEventListener('DOMContentLoaded', function() {
            const widget = document.getElementById('faq-chatbot-widget');
            const toggleBtn = document.getElementById('faq-toggle-btn');
            const closeBtn = document.getElementById('faq-close-btn');
            const panel = document.getElementById('faq-chatbot-panel');
            const form = document.getElementById('faq-form');
            const input = document.getElementById('faq-input');
            const messagesBox = document.getElementById('faq-messages');
            const quickSection = document.getElementById('faq-quick-section');
            const quickButtonsBox = document.getElementById('faq-quick-buttons');
            const moreHelpBox = document.getElementById('faq-more-help-box');
            const moreYesBtn = document.getElementById('faq-more-yes');
            const moreNoBtn = document.getElementById('faq-more-no');

            if (!widget || !toggleBtn || !panel) return;
            if (widget._x_dataStack) return;

            let isOpen = false;

            function setQuickVisible(visible) {
                if (quickSection) quickSection.style.display = visible ? 'block' : 'none';
            }

            function setMoreHelpVisible(visible) {
                if (moreHelpBox) moreHelpBox.style.display = visible ? 'block' : 'none';
            }

            function appendMsg(from, text, url = null, linkLabel = null) {
                if (!messagesBox) return;
                const row = document.createElement('div');
                row.style.display = 'flex';
                row.style.justifyContent = from === 'user' ? 'flex-end' : 'flex-start';
                row.style.flexShrink = '0';

                const bubble = document.createElement('div');
                bubble.style.maxWidth = from === 'user' ? '82%' : '88%';
                bubble.style.padding = '9px 13px';
                bubble.style.lineHeight = '1.45';
                bubble.style.overflowWrap = 'anywhere';
                bubble.style.borderRadius = from === 'user' ? '14px 14px 4px 14px' : '14px 14px 14px 4px';
                bubble.style.background = from === 'user' ? '#1F1B1A' : '#FFFFFF';
                bubble.style.color = from === 'user' ? '#FFFFFF' : '#2B2B2B';
                if (from !== 'user') {
                    bubble.style.border = '1px solid #E5E7EB';
                }

                const textNode = document.createElement('div');
                textNode.textContent = text;
                bubble.appendChild(textNode);

                if (url && from !== 'user') {
                    const linkWrap = document.createElement('div');
                    linkWrap.style.marginTop = '8px';
                    const link = document.createElement('a');
                    link.href = url;
                    link.textContent = (linkLabel || 'Ir a la sección') + ' →';
                    link.style.cssText = 'display:inline-block;background:#DB2777;color:#FFFFFF;text-decoration:none;font-size:11px;font-weight:600;padding:5px 10px;border-radius:8px;';
                    linkWrap.appendChild(link);
                    bubble.appendChild(linkWrap);
                }

                row.appendChild(bubble);
                messagesBox.insertBefore(row, moreHelpBox || quickSection || null);
                scrollFaqBottom();
            }

            if (quickButtonsBox && quickButtonsBox.children.length === 0) {
                siteKnowledge.slice(0, 4).forEach(faq => {
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.textContent = faq.question;
                    btn.style.cssText = 'background:#FFFFFF;color:#2B2B2B;border:1px solid #D1D5DB;border-radius:10px;padding:6px 10px;font-size:12px;text-align:left;cursor:pointer;max-width:100%;';
                    btn.addEventListener('click', function() {
                        setQuickVisible(false);
                        appendMsg('user', faq.question);
                        appendMsg('bot', faq.answer, faq.url, faq.linkLabel);
                        setMoreHelpVisible(true);
                        scrollFaqBottom();
                    });
                    quickButtonsBox.appendChild(btn);
                });
            }

            if (moreYesBtn) {
                moreYesBtn.addEventListener('click', function() {
                    setMoreHelpVisible(false);
                    appendMsg('user', 'Sí');
                    appendMsg('bot', 'Claro, elige una consulta rápida o escribe otra pregunta.');
                    setQuickVisible(true);
                    scrollFaqBottom();
                });
            }

            if (moreNoBtn) {
                moreNoBtn.addEventListener('click', function() {
                    setMoreHelpVisible(false);
                    appendMsg('user', 'No');
                    appendMsg('bot', '¡Perfecto! Si necesitas algo más, estaré aquí.');
                    setQuickVisible(true);
                    scrollFaqBottom();

                    setTimeout(function() {
                        isOpen = false;
                        syncFallbackPanel(false);
                    }, 650);
                });
            }

            toggleBtn.addEventListener('click', function() {
                isOpen = !isOpen;
                syncFallbackPanel(isOpen);
                if (isOpen) scrollFaqBottom();
            });

            if (closeBtn) {
                closeBtn.addEventListener('click', function() {
                    isOpen = false;
                    syncFallbackPanel(false);
                });
            }

            if (form && input) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const query = input.value.trim();
                    if (!query) return;

                    setQuickVisible(false);
                    setMoreHelpVisible(false);

                    appendMsg('user', query);
                    input.value = '';

                    const reply = buildAutonomousReply(query);
                    appendMsg('bot', reply.text, reply.url, reply.linkLabel);
                    setMoreHelpVisible(true);
                    scrollFaqBottom();
                });
            }
        });
    })();
</script>