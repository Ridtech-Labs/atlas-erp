<button
    type="button"
    x-cloak
    x-data="{}"
    x-on:click="$store.sidebar.isOpen ? $store.sidebar.close() : $store.sidebar.open()"
    class="atlas-topbar-toggle"
    aria-label="Toggle navigation"
>
    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M4 7h16"></path>
        <path d="M4 12h16"></path>
        <path d="M4 17h16"></path>
    </svg>
</button>
