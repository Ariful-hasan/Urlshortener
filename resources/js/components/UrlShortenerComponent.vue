<template>
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Url</div>

                    <div class="card-body">
                        <div class="input-group">
                            <input type="text" placeholder="url" class="form-control" aria-label="url"
                                aria-describedby="url" v-model="url_input">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-info" @click="saveUrl()">Short Url</button>
                            </div>

                        </div>
                        <div>
                            <blockquote v-if="result_txt" class="blockquote mt-5">
                                <p v-html="result_txt"></p>
                            </blockquote>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {

    data() {
        return {
            api: "http://localhost:80/api/short-url",
            url_input: "",
            result_txt: ""
        }
    },

    methods: {
        saveUrl() {
            if (this.url_input.length == "")
                return false;

            this.axios.post(this.api, { url: this.url_input })
            .then(res => {
                if (res.data !== 'undefined' && res.data.hasOwnProperty('data')) {
                    this.result_txt = this.showLink(res.data.data.original_url, res.data.data.short_url);
                }
                this.url_input = '';
            }).catch(err => {
                if (err.response.data !== 'undefine' && err.response.data.hasOwnProperty('message')) {
                    this.result_txt = '<b class="text-danger">'+err.response.data.message+'</b>';
                }
            });
        },

        showLink (original_url, short_url) {
            return '<a href="'+original_url+'" target="_blank">'+short_url+'</a>';
        }
    }
}
</script>
