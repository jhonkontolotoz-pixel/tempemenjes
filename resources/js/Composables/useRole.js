import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'

export function useRole() {
    const page = usePage()
    
    /**
     * Current authenticated user
     * @type {import('vue').ComputedRef<Object>}
     */
    const user = computed(() => page.props.auth?.user || null)
    
    /**
     * Current user's role
     * @type {import('vue').ComputedRef<Object|null>}
     */
    const role = computed(() => user.value?.role || null)
    
    /**
     * Current role name (lowercase)
     * @type {import('vue').ComputedRef<string|null>}
     */
    const roleName = computed(() => role.value?.name?.toLowerCase() || null)

    /**
     * Check if user has specific role
     * @param {string} roleToCheck - Role name to check (case-insensitive)
     * @returns {boolean}
     */
    const hasRole = (roleToCheck) => {
        if (!roleName.value || !roleToCheck) return false
        return roleName.value === roleToCheck.toLowerCase()
    }

    /**
     * Check if user has any of the specified roles
     * @param {string[]} roles - Array of role names to check
     * @returns {boolean}
     */
    const hasAnyRole = (roles) => {
        if (!Array.isArray(roles) || roles.length === 0) return false
        return roles.some(role => hasRole(role))
    }

    /**
     * Check if user can access (alias for hasAnyRole)
     * @param {string|string[]} roles - Role name or array of role names
     * @returns {boolean}
     */
    const canAccess = (roles) => {
        if (typeof roles === 'string') {
            return hasRole(roles)
        }
        return hasAnyRole(roles)
    }

    /**
     * Individual role checkers (reactive)
     */
    const isAdmin = computed(() => hasRole('admin'))
    const isManager = computed(() => hasRole('manager'))
    const isKasir = computed(() => hasRole('kasir'))
    const isUser = computed(() => hasRole('user'))

    /**
     * Check if user is authenticated
     * @type {import('vue').ComputedRef<boolean>}
     */
    const isAuthenticated = computed(() => !!user.value)

    /**
     * Check if user has admin or manager role
     * @type {import('vue').ComputedRef<boolean>}
     */
    const isAdminOrManager = computed(() => 
        isAdmin.value || isManager.value
    )

    /**
     * Get role display name (capitalized)
     * @type {import('vue').ComputedRef<string>}
     */
    const roleDisplayName = computed(() => {
        if (!role.value?.name) return 'Guest'
        return role.value.name.charAt(0).toUpperCase() + 
               role.value.name.slice(1)
    })

    /**
     * Get role badge color for UI
     * @type {import('vue').ComputedRef<string>}
     */
    const roleBadgeColor = computed(() => {
        const colors = {
            admin: 'danger',
            manager: 'primary',
            kasir: 'success',
            user: 'secondary',
        }
        return colors[roleName.value] || 'secondary'
    })

    return {
        // User data
        user,
        role,
        roleName,
        roleDisplayName,
        roleBadgeColor,
        
        // Auth status
        isAuthenticated,
        
        // Role checks (reactive)
        isAdmin,
        isManager,
        isKasir,
        isUser,
        isAdminOrManager,
        
        // Helper methods
        hasRole,
        hasAnyRole,
        canAccess,
    }
}