<template>
<div></div>
</template>

<script>
    import {loginService} from "../_services/login.service";

    export default {
        name: "Logout",
        created() {
            // reset login status
            loginService.logout();
            location.reload();

        },
    }
</script>
