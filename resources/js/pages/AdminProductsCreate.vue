<template>
    <div>
        <div v-if="isLoading" class="overlay-spinner">
            <div class="lds-dual-ring"></div>
        </div>
        <h2 class="title-1">Создать новый товар:</h2>
        <form @submit.prevent="onSubmit">
            <div class="row">
                <div class="col-md-8 col-lg-9">
                    <div class="form-group">
                        <label for="title" class="form-label">Наименование</label>
                        <input type="text"
                               v-model="title"
                               :class="{'is-invalid': errors.title}"
                               placeholder="Наименование товара"
                               class="form-control" id="title">
                        <div class="invalid-feedback" v-if="errors.title">{{errors.title[0]}}</div>
                    </div>
                    <div class="form-group">
                        <label for="subtitle" class="form-label">Подзаголовок</label>
                        <input type="text"
                               v-model="subtitle"
                               :class="{'is-invalid': errors.subtitle}"
                               placeholder="Подзаголовок товара"
                               class="form-control" id="subtitle">
                        <div class="invalid-feedback" v-if="errors.subtitle">{{errors.subtitle[0]}}</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Описание товара</label>
                        <ckeditor :editor="editor" v-model="body" :config="editorConfig"></ckeditor>
                    </div>
                </div>
                <div class="col-md-4 col-lg-3">
                    <div class="form-group">
                        <label class="form-label">Изображение</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="customFile" @change="getImage($event)">
                            <label class="custom-file-label" for="customFile">Картинка</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <img class="product-preview" :src="imagePreviewSrc" alt="">
                    </div>
                    <div class="form-group">
                        <label for="price" class="form-label">Цена</label>
                        <input type="number"
                               v-model="price"
                               :class="{'is-invalid': errors.price}"
                               class="form-control" id="price">
                        <div class="invalid-feedback" v-if="errors.price">{{errors.price[0]}}</div>
                    </div>
                    <div class="form-group">
                        <select v-model="selectedTag" class="custom-select">
                            <option value="0">Без тега</option>
                            <option v-for="t in tags" :key="t.id" :value="t.id">{{t.name}}</option>
                        </select>
                    </div>
                </div>
            </div>
            <button class="btn btn-success">Создать товар</button>
        </form>
    </div>
</template>

<script>
    import ClassicEditor from '@ckeditor/ckeditor5-build-classic'
    import CKEditor from '@ckeditor/ckeditor5-vue'
    import {api} from '@/api'
    import {mapState} from 'vuex'

    export default {
        mounted() {
            api.get('tags?all=1')
                .then(resp => {
                    this.tags = resp.data
                })
        },
        data() {
            return {
                selectedTag: 0,
                tags: [],
                title: '',
                subtitle: '',
                body: '',
                price: '',
                image: null,
                imagePreviewSrc: 'http://via.placeholder.com/450x300',
                // ckeditor
                editor: ClassicEditor,
                editorConfig: {},
            }
        },
        computed: {
            ...mapState({
                errors: state => state.admin.errors,
                isLoading: state => state.cart.isLoading
            })
        },
        methods: {
            onSubmit() {
                let data = new FormData();
                data.append('title', this.title)
                if (this.subtitle) {
                    data.append('subtitle', this.subtitle)
                }
                if (this.image) {
                    data.append('image', this.image)
                }
                if (this.body) {
                    data.append('body', this.body)
                }
                if (this.price) {
                    data.append('price', this.price)
                }
                data.append('tagId', this.selectedTag)
                this.$store.dispatch('createProduct', data)
                    .then(() => this.$router.push({name: 'admin-products-list'}))
            },
            getImage(event) {
                this.image = event.target.files[0]
                this.imagePreviewSrc = URL.createObjectURL(this.image)
            }
        },
        components: {
            ckeditor: CKEditor.component
        }
    }
</script>

<style>
    .ck-editor__editable {
        min-height: 300px;
    }
</style>
