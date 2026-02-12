// resources/js/Composables/useRole.js
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

export function useRole() {
    const user = computed(() => usePage().props.auth.user);
    const role = computed(() => user.value?.role);

    const hasRole = (roleName) => {
        return role.value?.name === roleName;
    };

    const isAdmin = computed(() => hasRole('admin'));
    const isManager = computed(() => hasRole('manager'));
    const isKasir = computed(() => hasRole('kasir'));
    const isUser = computed(() => hasRole('user'));

    return {
        user,
        role,
        hasRole,
        isAdmin,
        isManager,
        isKasir,
        isUser,
    };
}