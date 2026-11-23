export function reservationForm(el) {
    const cfg = window.reservationConfig || {};
    const d = el.dataset;
    const serviceId = Number(d.serviceId || cfg.serviceId);
    const initialEmployees = Array.isArray(cfg.employees)
        ? cfg.employees.map(e => ({ ...e, id: String(e.id) }))
        : JSON.parse(d.employees || '[]').map(e => ({ ...e, id: String(e.id) }));

    return {
        employeeId: '',
        date: cfg.tomorrow || d.tomorrow,
        slots: [],
        selectedTime: '',
        loading: false,

        calOpen: false,
        viewYear: Number(cfg.viewYear || d.viewYear),
        viewMonth: Number(cfg.viewMonth || d.viewMonth),
        minDate: cfg.today || d.today,
        maxDate: cfg.maxDate || d.maxDate,
        blockedDays: {},
        fullDays: {},

        employees: initialEmployees,

        init() {
            if (this.date) {
                const [y, m] = this.date.split('-').map(Number);
                this.viewYear = y;
                this.viewMonth = m;
            }
            this.loadSlots();
            this.loadMonthAvailability();
        },

        selectedEmployeeName() {
            if (!this.employeeId) return 'Cualquiera disponible';
            const e = this.employees.find(x => String(x.id) === String(this.employeeId));
            return e ? e.name : 'Cualquiera disponible';
        },

        selectedEmployeeRole() {
            if (!this.employeeId) return 'El primero libre';
            const e = this.employees.find(x => String(x.id) === String(this.employeeId));
            return e ? e.role : 'El primero libre';
        },

        selectedEmployeeInitial() {
            const name = this.selectedEmployeeName();
            return name ? name.charAt(0).toUpperCase() : '?';
        },

        selectEmployee(id) {
            this.employeeId = id ? String(id) : '';
            this.fullDays = {};
            this.loadSlots();
            this.loadMonthAvailability();
        },

        get monthLabel() {
            const months = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
            return `${months[this.viewMonth - 1]} ${this.viewYear}`;
        },

        get formattedDate() {
            if (!this.date) return 'Selecciona una fecha';
            const [y, m, day] = this.date.split('-').map(Number);
            const months = ['ene','feb','mar','abr','may','jun','jul','ago','sep','oct','nov','dic'];
            return `${day} ${months[m - 1]} ${y}`;
        },

        get calendarDays() {
            const days = [];
            const first = new Date(this.viewYear, this.viewMonth - 1, 1);
            const last = new Date(this.viewYear, this.viewMonth, 0);

            let startWeekday = first.getDay();
            startWeekday = startWeekday === 0 ? 6 : startWeekday - 1;

            for (let i = 0; i < startWeekday; i++) {
                days.push({ date: null, label: '', enabled: false, blocked: false, isToday: false });
            }

            for (let dayNum = 1; dayNum <= last.getDate(); dayNum++) {
                const dateStr = `${this.viewYear}-${String(this.viewMonth).padStart(2, '0')}-${String(dayNum).padStart(2, '0')}`;
                const inRange = dateStr >= this.minDate && dateStr <= this.maxDate;
                const blocked = !!this.blockedDays[dateStr] || !!this.fullDays[dateStr];
                days.push({
                    date: dateStr,
                    label: dayNum,
                    enabled: inRange,
                    blocked,
                    isToday: dateStr === this.minDate,
                });
            }

            return days;
        },

        prevMonth() {
            if (this.viewMonth === 1) {
                this.viewMonth = 12;
                this.viewYear--;
            } else {
                this.viewMonth--;
            }
            this.loadMonthAvailability();
        },

        nextMonth() {
            if (this.viewMonth === 12) {
                this.viewMonth = 1;
                this.viewYear++;
            } else {
                this.viewMonth++;
            }
            this.loadMonthAvailability();
        },

        selectDay(day) {
            if (!day.enabled || day.blocked || !day.date) return;
            this.date = day.date;
            this.calOpen = false;
            this.loadSlots();
        },

        async loadMonthAvailability() {
            try {
                const params = new URLSearchParams({
                    service_id: serviceId,
                    year: this.viewYear,
                    month: this.viewMonth,
                });
                if (this.employeeId) params.append('employee_id', this.employeeId);

                const monthUrl = cfg.routes?.month || d.routeMonth;
                const res = await fetch(`${monthUrl}?${params}`, {
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                });
                const data = await res.json();

                if (data && data.days) {
                    const nextBlocked = { ...this.blockedDays };
                    Object.entries(data.days).forEach(([k, v]) => {
                        const isAvailable = (typeof v === 'object' && v !== null)
                            ? (v.available ?? v.enabled ?? false)
                            : !!v;
                        nextBlocked[k] = !isAvailable;
                    });
                    this.blockedDays = nextBlocked;
                }
            } catch (e) {}
        },

        async loadSlots() {
            this.loading = true;
            this.selectedTime = '';
            try {
                const params = new URLSearchParams({
                    service_id: serviceId,
                    date: this.date,
                });
                if (this.employeeId) params.append('employee_id', this.employeeId);

                const slotsUrl = cfg.routes?.slots || d.routeSlots;
                const res = await fetch(`${slotsUrl}?${params}`, {
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                });
                const data = await res.json();
                this.slots = data.slots || [];

                const anyAvailable = this.slots.some(s => s.available);
                if (this.slots.length > 0 && !anyAvailable) {
                    this.fullDays = { ...this.fullDays, [this.date]: true };
                }
            } catch (e) {
                this.slots = [];
            } finally {
                this.loading = false;
            }
        }
    };
}

export default reservationForm;

window.reservationForm = reservationForm;

document.addEventListener('alpine:init', () => {
    if (window.Alpine) {
        window.Alpine.data('reservationForm', reservationForm);
    }
});