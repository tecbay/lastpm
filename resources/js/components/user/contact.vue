<template>
    <div class="">
        ascas
    </div>
</template>

<script>
    export default {
        methods:{
            getContacts: function () {
                var self = this;
                self.loadingContact = true;
                var self = this;
                $.ajax({
                    url: "{{route('get.contacts')}}",
                    method: "GET",
                    dataType: 'json',
                    success: function (d) {
                        self.contactList = d
                        self.loadingContact = false;
                    },
                    error: function (xhr, textStatus, errorThrown) {

                    }
                });
            }
        }
    }
</script>