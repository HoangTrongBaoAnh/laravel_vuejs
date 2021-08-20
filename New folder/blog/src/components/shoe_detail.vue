<template>
  <div>
    <Nav class="pd-2" />
    <div class="container p-2" v-if="posts">
      <p v-text="posts.title"></p>
      <p v-text="posts.category"></p>
      <p v-text="posts.price"></p>
      <button @click="$store.commit('addToCart', posts)">Add to Cart</button>
    </div>
  </div>
</template>
<script>
import Nav from "./nav_header";
import baseRequest from "../core/baseRequest";
export default {
  data() {
    return {
      posts: [],
    };
  },
  mounted() {
    this.getPosts(this.$route.params.id);
  },
  methods: {
    getPosts: function (id) {
      this.loading = true;
      baseRequest.post("shoe/show/" + id).then((response) => {
        this.posts = response.data;
        console.log(this.posts);
      });
    },
  },
  computed: {
    /*
        products(){
            return this.$store.state.products;
        },
        product(){
            return this.products.find(product => product.id === this.$route.params.id)
        }
        */
  },
  components: {
    Nav,
  },
};
</script>
