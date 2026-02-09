import { ref, watch, onMounted } from 'vue'

export function useTheme() {
    const isDark = ref(false)

    // Get theme from localStorage or system preference
    const getInitialTheme = () => {
        const stored = localStorage.getItem('theme')
        if (stored) {
            return stored === 'dark'
        }
        // Check system preference
        return window.matchMedia('(prefers-color-scheme: dark)').matches
    }

    // Apply theme to document
    const applyTheme = (dark) => {
        if (dark) {
            document.documentElement.setAttribute('data-bs-theme', 'dark')
            document.body.classList.add('dark-mode')
        } else {
            document.documentElement.setAttribute('data-bs-theme', 'light')
            document.body.classList.remove('dark-mode')
        }
        localStorage.setItem('theme', dark ? 'dark' : 'light')
    }

    // Toggle theme
    const toggleTheme = () => {
        isDark.value = !isDark.value
    }

    // Initialize theme on mount
    onMounted(() => {
        isDark.value = getInitialTheme()
        applyTheme(isDark.value)
    })

    // Watch for theme changes
    watch(isDark, (newValue) => {
        applyTheme(newValue)
    })

    return {
        isDark,
        toggleTheme
    }
}