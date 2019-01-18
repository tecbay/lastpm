<template>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-lg-12">
            <el-progress :show-text="true" :text-inside="true" :stroke-width="18" :percentage="diskUsed"
                         v-bind:status="diskStatus(diskUsed)">

            </el-progress>
            <p class="text-center">Disk Used : <span v-text="diskUsed"></span> % </p>
            <hr>
        </div>
        <div class="col-md-3 col-sm-3 col-lg-3">
            <ul class="list-group" style="background: #fff">
                <li class="px-3 pt-2 d-flex justify-content-between align-items-center">
                    <button @click="getContacts()" data-toggle="modal" data-target="#crF"
                            class="btn btn-info btn-block">
                        <i class="icon-btn fa fa-folder-plus"></i>
                        &nbsp;Create Folder
                    </button>

                </li>
                <h5 class="m-0 p-0">
                    <hr>
                </h5>
                <li v-for="(folder,index) in folders" v-if="folders" class="list-group-item"
                    v-bind:class="currentFolder==folder.id ? 'active' : ''"
                    style="border: none!important">

                    <a @click="OnClickFolder(folder.id,folder.name,index)" href="#">
                        <i class="far"
                           v-bind:class="currentFolder==folder.id ? 'fa-folder-open' : 'fa-folder'"
                           v-html="' '+folder.name"
                           style="font-size: 22px"
                        ></i>

                    </a>
                </li>
                <li style="border: none!important;padding-left: 20px!important" class="list-group-item"
                    v-if="folders.length==0">
                    <p class="text-center" v-text="folderNotFound"></p>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                diskUsed: 0,
                currentFolder: 0,
                currentFolderIndex: 0,
                currentFolderObj: null,
                folders: [],
                folderNotFound: 'You haven\'t create any folder yet',
                fileName: 'File Name',
                fileSize: 'File Size',
                fileUpdateAt: 'Uploaded',
                Action: 'Action',
                files: [],
                contactList: [],
                selectedContact: [],
                folderName: null,
                search: '',
                haveAccess: [],
                haveNotAccess: [],
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            }
        },
        mounted() {
            this.getDiskUsed();
            console.log('as...');

            this.$store.state.count++
        },
        methods: {
            diskStatus: function (val) {
                if (val >= 90) {
                    return 'exception'
                }
                return 'success'
            },
            getDiskUsed() {
                var self = this;
                $.ajax({
                    url: '/disk-used',
                    method: "GET",
                    dataType: 'json',
                    success: function (d) {
                        console.log(d);
                        self.diskUsed = d
                    }
                });
            },
        }
    }
</script>

<style scoped>

</style>