export default {
    install: (app, options) => {
        const user = JSON.parse(localStorage.getItem('user'))
        const SUPER_ADMIN_KEY = 'Super Admin'
        if (user === null) {
            return false
        }
        const isSuperAdmin = user.role_names.includes(SUPER_ADMIN_KEY)
        axios.defaults.headers.common['Authorization'] = 'Bearer ' + user.token
        app.config.globalProperties.can = function (value) {
            let permissions = user.permission_names
            let _return = false
            if (!Array.isArray(permissions)) {
                return false
            }
            if (value.includes('|')) {
                value.split('|').forEach(function (item) {
                    if (permissions.includes(item.trim())) {
                        _return = true
                    }
                })
            } else if (value.includes('&')) {
                _return = true
                value.split('&').forEach(function (item) {
                    if (!permissions.includes(item.trim())) {
                        _return = false
                    }
                })
            } else {
                _return = permissions.includes(value.trim())
            }
            return isSuperAdmin ? true : _return
        }
        app.config.globalProperties.is = function (value) {
            let roles = user.role_names
            let _return = false
            if (!Array.isArray(roles)) {
                return false
            }
            if (value.includes('|')) {
                value.split('|').forEach(function (item) {
                    if (roles.includes(item.trim())) {
                        _return = true
                    }
                })
            } else if (value.includes('&')) {
                _return = true
                value.split('&').forEach(function (item) {
                    if (!roles.includes(item.trim())) {
                        _return = false
                    }
                })
            } else {
                _return = roles.includes(value.trim())
            }
            return isSuperAdmin ? true : _return
        }
        axios.interceptors.response.use(function (response) {
            return response;
        }, function (error) {
            if (error.code === 'ERR_BAD_REQUEST') {
                //localStorage.removeItem('user')
                //location.href = '/login'
            }
        });
    }
}
