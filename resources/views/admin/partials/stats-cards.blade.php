<div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
    <x-admin.stat-card 
        title="Reservas Hoy" 
        :value="$reservasHoy" 
        icon="📅" 
        color="green"
        subtitle="Citas agendadas para hoy" 
    />

    <x-admin.stat-card 
        title="Esta Semana" 
        :value="$reservasSemana" 
        icon="📊" 
        color="gray"
        subtitle="Reservas en total" 
    />

    <x-admin.stat-card 
        title="Ingresos (Hoy)" 
        :value="'$' . number_format($ingresosHoy, 0, ',', '.')" 
        icon="💰" 
        color="pink"
        subtitle="Pago al retirar en tienda" 
    />

    <x-admin.stat-card 
        title="Personal" 
        :value="$totalEstilistas" 
        icon="👥" 
        color="gray"
        subtitle="Estilistas activos" 
    />
</div>